package main

import (
	"crypto/tls"
	"crypto/x509"
	"fmt"
	"log"
	"net/http"
	"os"
)

func helloHandler(w http.ResponseWriter, r *http.Request) {
	fmt.Fprintf(w, "Hello, mTLS client!")
}

func main() {
	serverCert, serverKey := "server-cert.pem", "server-key.pem"
	cert, err := tls.LoadX509KeyPair(serverCert, serverKey)
	if err != nil {
		log.Fatalf("Error loading server keypair: %v", err)
	}

	// Caricamento del certificato CA del client (per autenticare i client)
	caCertFile := "ca-cert.pem"
	caCert, err := os.ReadFile(caCertFile)
	if err != nil {
		log.Fatalf("Errore nel caricamento del certificato CA: %v", err)
	}

	// Creazione del pool di certificati CA
	caCertPool := x509.NewCertPool()
	if ok := caCertPool.AppendCertsFromPEM(caCert); !ok {
		log.Fatalf("Errore nell'aggiunta del certificato CA al pool")
	}

	// Configurazione TLS per il server
	tlsConfig := &tls.Config{
		Certificates: []tls.Certificate{cert},        // Certificato del server
		ClientCAs:    caCertPool,                     // Certificati CA per verificare i client
		ClientAuth:   tls.RequireAndVerifyClientCert, // Richiede certificati client validi
	}

	// Creazione del server HTTP con supporto TLS
	server := &http.Server{
		Addr:      ":8443",
		TLSConfig: tlsConfig,
	}

	// Registrazione degli handler
	http.HandleFunc("/", helloHandler)

	// Avvio del server
	log.Println("Server mTLS in esecuzione sulla porta 8443")
	if err := server.ListenAndServeTLS("", ""); err != nil {
		log.Fatalf("Errore durante l'esecuzione del server: %v", err)
	}
}
