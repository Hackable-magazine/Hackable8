//#include <avr/io.h>
//#include <util/delay.h>
//#include <inttypes.h>
//#include <string.h>
//#include <stdlib.h>
//#include <stdint.h>

// vitesse port série
#define BAUD_RATE 115200

// initialisation port série
void serial_init() {
  // calcul valeur du registre
  #if BAUD_RATE < 57600
    // basse vitesse
    uint16_t UBRR0_value = ((F_CPU / (8L * BAUD_RATE)) - 1)/2 ;
    UCSR0A &= ~(1 << U2X0); // doubleur vitesse off
  #else
    // haute vitesse
    uint16_t UBRR0_value = ((F_CPU / (4L * BAUD_RATE)) - 1)/2;
    UCSR0A |= (1 << U2X0);  // doubleur vitesse on
  #endif
  
  // écriture des registres en deux fois (haut/bas)
  UBRR0H = UBRR0_value >> 8;
  UBRR0L = UBRR0_value;
            
  // Activer l'émission
  UCSR0B |= 1<<TXEN0;

  // Le reste est en valeur par défauts
  // 8 bit, pas de parité, 1 bit de stop
}

// fonction d'envoi d'un caractère
static void putchr(char c) {
  // on attend que ce soit disponible/utilisable
  loop_until_bit_is_set(UCSR0A, UDRE0);
  // écriture registre = envoi de l'octet
  UDR0 = c;
}

// fonction d'envoi d'une chaine de caractères
// utilisant putchr()
static void printstr(const char *s) {
  // on boucle dans qu'il y a des caractères
  while (*s) {
    // et on change LF en CR à la fin
    if (*s == '\n')
      putchr('\r');
    putchr(*s++);
  }
}

int main(void) {
  // appel de notre fonction pour configurer le port
  serial_init();
  
  // PB5 = port 13 sur Uno (led)
  // broche en sortie
  DDRB |= _BV(PB5);
  
  // pause en milliseconde (fournie par AVR Libc)
  _delay_ms(500);
  
  // boucle sans fin
  for(;;) {
    // port à 1 (+5V)
    PORTB |= _BV(PB5);
    // pause
    _delay_ms(200);
    // port à 0 (0V)
    PORTB &= ~_BV(PB5);
    
    // envoi du message sur le port série
    printstr("Coucou monde\n");
    
    // pause
    _delay_ms(500);
  }
  
  // on n'arrive jamais ici
  return 0;
}
