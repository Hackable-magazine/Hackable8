void setupMoniteur() {
  Serial.begin(115200);
}

void loopMoniteur() {
  Serial.print("Compteur= R:");
  Serial.print(compteurR);
  Serial.print("  V:");
  Serial.print(compteurV);
  Serial.print("  B:");
  Serial.print(compteurB);
  Serial.print("  T:");
  Serial.print(compteur);
  Serial.print("  D:");
  Serial.println(compteur-(compteurR+compteurV+compteurB));
  delay(1000);
}
