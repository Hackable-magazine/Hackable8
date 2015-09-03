#define LED GREEN_LED

void setupVert() {                
  pinMode(LED, OUTPUT);     
}

void loopVert() {
  digitalWrite(LED, HIGH);
  delay(202);
  digitalWrite(LED, LOW);
  delay(202);
  compteur++;
  compteurV++;
}
