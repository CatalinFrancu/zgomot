/**
 * Definim zgomotul ca fiind o perioadă de timp care începe la o amplitudine
 * mai mare decât NOISE_START și durează cât timp există amplitudini mai mari
 * decât NOISE_REFRESH la distanțe mai mici decât NOISE_GRACE secunde.
 *
 * Programul acceptă o dată și extrage toate zgomotele din toate fișierele
 * audio pentru acea dată.
 **/

#include <regex.h>
#include <stdarg.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

#define SAMPLE_RATE 16000    /* eșantioane pe secundă */
#define BUF_SIZE 16384       /* buffer pentru citirea de la mpg123 */
#define HEADER_SIZE 44       /* numărul de octeți dinaintea datelor propriu-zise */
#define IGNORE_SECONDS 2     /* ignoră primele două secunde - păcănituri etc. */
#define INPUT_FILE_PATTERN "../mp3/%04d/%02d/%02d/%04d-%02d-%02d-%02d.mp3"
#define OUTPUT_FILE_PATTERN "../clip/%04d/%02d/%02d/%04d-%02d-%02d-%02d-%02d-%02d.mp3"

#define NOISE_START 8000
#define NOISE_REFRESH 6000
#define NOISE_GRACE 60

#define MIN(X, Y) (((X) < (Y)) ? (X) : (Y))
#define MAX(X, Y) (((X) > (Y)) ? (X) : (Y))

/**
 * Octeții pe care îi conține formatul WAV. Zerourile denotă valori variabile.
 **/
char EXPECTED_HEADER[HEADER_SIZE] = {
  'R', 'I', 'F', 'F', 0, 0, 0, 0, 'W', 'A',
  'V', 'E', 'f', 'm', 't', ' ', 0, 0, 0, 0,
  0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
  0, 0, 0, 0, 0, 0, 'd', 'a', 't', 'a',
  0, 0, 0, 0,
};

unsigned char buf[BUF_SIZE];
char filename[100], command[100];

/**
 * Tipărește un mesaj de eroare și termină execuția.
 **/
void die(const char *format, ...) {
  va_list args;
  va_start(args, format);
  vfprintf(stderr, format, args);
  fprintf(stderr, "\n");
  va_end(args);
  exit(1);
}

void usage() {
  fprintf(stderr, "Folosire: clip [opțiuni] AAAA-LL-ZZ\n");
  fprintf(stderr, "\n");
  fprintf(stderr, "  -q = suprimă unele mesaje informative\n");
  exit(1);
}

/* Citește 16 biți cu semn în ordine little-endian. */
short readS16(unsigned char *c, int pos) {
  return (((short)c[pos+ 1]) << 8) + c[pos];
}

/* Citește 32 de biți fără semn în ordine little-endian. */
int readU32(unsigned char *c, int pos) {
  return (c[pos + 3] << 24) + (c[pos + 2] << 16) + (c[pos + 1] << 8) + c[pos];
}

/* Citește headerul verifică-i integritatea. */
void sanityCheckHeader(FILE *p) {
  unsigned char header[HEADER_SIZE];
  fread(header, HEADER_SIZE, 1, p);

  for (int i = 0; i < HEADER_SIZE; i++) {
    if (EXPECTED_HEADER[i] && header[i] != EXPECTED_HEADER[i]) {
      die("Octet incorect la poziția %d (%d în loc de %d)",
          i, header[i], EXPECTED_HEADER[i]);
    }
  }

  int x = readU32(header, 16);
  if (x != 16) {
    die("Subchunk size incorect: %d în loc de 16", x);
  }

  x = readS16(header, 20);
  if (x != 1) {
    die("Format audio incorect: %d în loc de 1", x);
  }

  x = readS16(header, 22);
  if (x != 1) {
    die("Număr de canale incorect: %d în loc de 1", x);
  }

  x = readU32(header, 24);
  if (x != SAMPLE_RATE) {
    die("Sample rate incorect: %d în loc de %d", x, SAMPLE_RATE);
  }

  x = readU32(header, 28);
  if (x != 2 * SAMPLE_RATE) {
    die("Byte rate incorect: %d în loc de %d", x, 2 * SAMPLE_RATE);
  }

  x = readS16(header, 32);
  if (x != 2) {
    die("Aliniere de bloc incorectă: %d în loc de 2");
  }

  x = readS16(header, 34);
  if (x != 16) {
    die("Bits per sample incorect: %d în loc de 16");
  }
}

/**
 * Decupează bucata din filename între startFrame și endFrame și o salvează
 * într-un alt fișier MP3. endFrame poate fi -1, cu sensul de „până la
 * sfârșit”.
 **/
void clipWave(int startFrame, int endFrame, char *filename, int year, int month, int day, int hour) {
  int startSecond = startFrame / SAMPLE_RATE;
  startSecond = MAX(startSecond - 1, 0); /* începe decuparea cu o secundă înainte */
  int startMinute = startSecond / 60;
  startSecond %= 60;

  char outputFile[100];
  sprintf(outputFile, OUTPUT_FILE_PATTERN,
          year, month, day, year, month, day, hour,
          startMinute, startSecond);

  /* creăm directorul */
  char command[1000];
  sprintf(command, "mkdir -p `dirname %s`", outputFile);
  system(command);

  /* decupăm fișierul */
  if (endFrame != -1) {
    int duration = (endFrame - startFrame) / SAMPLE_RATE;
    duration += 4; /* adăugăm câteva secunde pentru rotunjire */
    sprintf(command, "ffmpeg -loglevel quiet -y -ss %02d:%02d -i %s -t %d -acodec copy %s",
            startMinute, startSecond, filename, duration, outputFile);
  } else {
    sprintf(command, "ffmpeg -loglevel quiet -y -ss %02d:%02d -i %s -acodec copy %s",
            startMinute, startSecond, filename, outputFile);
  }
  system(command);
}

int main(int argc, char **argv) {
  /* Citește argumentele din linia de comandă. */
  int quietFlag = 0, c;

  opterr = 0;
  while ((c = getopt(argc, argv, "q")) != -1) {
    switch (c) {

      case 'q':
        quietFlag = 1;
        break;

      default:
        usage();
    }
  }

  /* Trebuie să existe un singur argument non-opțiune: data. */
  if (optind != argc - 1) {
    usage();
  }
  char *date = argv[argc - 1];

  /* Verifică corectitudinea datei. */
  regex_t regex;
  regcomp(&regex, "[0-9]{4}-[0-9]{2}-[0-9]{2}", REG_EXTENDED);
  if (regexec(&regex, date, 0, NULL, 0)) {
    die("Argumentul trebuie să fie o dată în formatul AAAA-LL-ZZ");
  }

  int year, month, day;
  sscanf(date, "%d-%d-%d", &year, &month, &day);

  /* Iterează prin 24 ore. */
  for (int hour = 0; hour < 24; hour++) {

    int noise = 0;      /* 1/0 după cum suntem în stare de zgomot sau nu */
    int noiseStart;     /* eșantionul la care a început zgomotul curent */
    int noiseRefresh;   /* eșantionul la care a fost reîmprospătat zgomotul curent */

    /* Află fișierul de citit. */
    sprintf(filename, INPUT_FILE_PATTERN, year, month, day, year, month, day, hour);

    if (access(filename, F_OK) != 0) {
      printf("Nu găsesc fișierul [%s].\n", filename);
    } else {
      /* Lansează procesul mpg123 pentru convertirea la .wav. */
      if (!quietFlag) {
        printf("Citesc fișierul [%s].\n", filename);
      }

      sprintf(command, "mpg123 -q -w - %s 2>/dev/null", filename);
      FILE *p = popen(command, "r");
      if (!p) {
        die("Nu pot lansa procesul mpg123.");
      }

      sanityCheckHeader(p);

      /* Citește datele audio. */
      int samples = 0; /* numărul de eșantioane */
      int len;

      do {
        /* Citește următorul bloc și parcurge-l. */
        len = fread(buf, 1, BUF_SIZE, p);

        for (int i = 0; i < len; i += 2) {
          short sample = readS16(buf, i);
          /* Înlocuiește primele eșantioane cu liniște pentru a evita
           * zgomotele inițiale ale microfonului. */
          if (samples < IGNORE_SECONDS * SAMPLE_RATE) {
            sample = -32768;
          }

          /* Actualizează informațiile despre zgomot și liniște. */
          if (noise) {
            if (sample >= NOISE_REFRESH) {
              /* zgomotul este reîmprospătat */
              noiseRefresh = samples;
            } else {
              /* zgomotul nu este reîmprospătat */
              if (samples - noiseRefresh >= SAMPLE_RATE * NOISE_GRACE) {
                /* zgomotul se termină */
                noise = 0;
                clipWave(noiseStart, noiseRefresh, filename, year, month, day, hour);
              }
            }
          } else {
            if (sample >= NOISE_START) {
              /* începe un nou zgomot */
              noise = 1;
              noiseStart = noiseRefresh = samples;
            } else {
              /* liniștea continuă */
            }
          }

          samples++;
        }
      } while (len);

      if (noise) {
        clipWave(noiseStart, -1, filename, year, month, day, hour);
      }

      /* Termină procesul mpg123. */
      pclose(p);

      /* Tipărește date globale despre fișier. */
      if (!quietFlag) {
        int seconds = samples / SAMPLE_RATE;
        printf("Durată: %d minute %d secunde\n", seconds / 60, seconds % 60);
      }
    }
  }

  return 0;
}
