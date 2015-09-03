#define LED RED_LED

void setupRouge() {                
  pinMode(LED, OUTPUT);     
}

void loopRouge() {
  digitalWrite(LED, HIGH);
  delay(201);
  digitalWrite(LED, LOW);
  delay(201);
  compteur++;
  compteurR++;
}
