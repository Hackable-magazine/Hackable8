#define LED BLUE_LED

unsigned int compteur;
unsigned int compteurR;
unsigned int compteurV;
unsigned int compteurB;

void setup() {
  pinMode(LED, OUTPUT);
}

void loop() {
  digitalWrite(LED, HIGH);
  delay(200);
  digitalWrite(LED, LOW);
  delay(200);
  compteur++;
  compteurB++;
}
