package main

import (
	"bufio"
	"fmt"
	"os"
	"strings"
	"time"
)

 
type Kullanici struct {
	KullaniciAdi string
	Sifre        string
	Tip          string
}

 
var kullanicilar = []Kullanici{
	{"admin", "admin123", "admin"},
	{"musteri", "musteri123", "musteri"},
}

 
func logKaydet(eylem string) {
	dosya, err := os.OpenFile("log.txt", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
	if err != nil {
		fmt.Println("Log dosyası açılamadı:", err)
		return
	}
	defer dosya.Close()

	logGirisi := fmt.Sprintf("%s: %s\n", time.Now().Format("2006-01-02 15:04:05"), eylem)
	_, err = dosya.WriteString(logGirisi)
	if err != nil {
		fmt.Println("Log dosyasına yazılamadı:", err)
	}
}

 
func dogrulama() *Kullanici {
	reader := bufio.NewReader(os.Stdin)

	// Kullanıcı tipi seçimi
	fmt.Print("Kullanıcı tipi seçin (0: Admin, 1: Müşteri): ")
	tipGiris, _ := reader.ReadString('\n')
	tipGiris = strings.TrimSpace(tipGiris)

	var tip string
	if tipGiris == "0" {
		tip = "admin"
	} else if tipGiris == "1" {
		tip = "musteri"
	} else {
		fmt.Println("Geçersiz giriş")
		return nil
	}

	 
	fmt.Print("Kullanıcı adınızı girin: ")
	kullaniciAdi, _ := reader.ReadString('\n')
	kullaniciAdi = strings.TrimSpace(kullaniciAdi)

	fmt.Print("Şifrenizi girin: ")
	sifre, _ := reader.ReadString('\n')
	sifre = strings.TrimSpace(sifre)

	 
	for _, kullanici := range kullanicilar {
		if kullanici.KullaniciAdi == kullaniciAdi && kullanici.Sifre == sifre && kullanici.Tip == tip {
			logKaydet(fmt.Sprintf("%s başarılı bir şekilde giriş yaptı", kullaniciAdi))
			return &kullanici
		}
	}

	logKaydet(fmt.Sprintf("%s için başarısız giriş denemesi", kullaniciAdi))
	fmt.Println("Geçersiz kullanıcı adı veya şifre")
	return nil
}

 
func profilGoster(kullanici *Kullanici) {
	fmt.Println("\nProfil Bilgileriniz:")
	fmt.Printf("Kullanıcı Adı: %s\n", kullanici.KullaniciAdi)
	fmt.Printf("Şifre: %s\n", kullanici.Sifre)
}

 
func sifreDegistir(kullanici *Kullanici) {
	reader := bufio.NewReader(os.Stdin)
	fmt.Print("Yeni şifreyi girin: ")
	yeniSifre, _ := reader.ReadString('\n')
	yeniSifre = strings.TrimSpace(yeniSifre)

	for i := range kullanicilar {
		if kullanicilar[i].KullaniciAdi == kullanici.KullaniciAdi {
			kullanicilar[i].Sifre = yeniSifre
			break
		}
	}

	logKaydet(fmt.Sprintf("%s şifresini değiştirdi", kullanici.KullaniciAdi))
	fmt.Println("Şifre başarıyla değiştirildi")
}

 
func adminMenu(kullanici *Kullanici) {
	reader := bufio.NewReader(os.Stdin)
	for {
		fmt.Println("\nAdmin Menüsü:")
		fmt.Println("1. Müşteri Ekle")
		fmt.Println("2. Müşteri Sil")
		fmt.Println("3. Logları Görüntüle")
		fmt.Println("4. Çıkış Yap")
		fmt.Print("Seçiminizi yapın: ")
		secim, _ := reader.ReadString('\n')
		secim = strings.TrimSpace(secim)

		switch secim {
		case "1":
			musteriEkle()
		case "2":
			musteriSil()
		case "3":
			loglariGoster()
		case "4":
			logKaydet(fmt.Sprintf("%s çıkış yaptı", kullanici.KullaniciAdi))
			fmt.Println("Çıkış yapılıyor...")
			return
		default:
			fmt.Println("Geçersiz seçim")
		}
	}
}

 
func musteriMenu(kullanici *Kullanici) {
	reader := bufio.NewReader(os.Stdin)
	for {
		fmt.Println("\nMüşteri Menüsü:")
		fmt.Println("1. Profil Bilgilerini Görüntüle")
		fmt.Println("2. Şifre Değiştir")
		fmt.Println("3. Çıkış Yap")
		fmt.Print("Seçiminizi yapın: ")
		secim, _ := reader.ReadString('\n')
		secim = strings.TrimSpace(secim)

		switch secim {
		case "1":
			profilGoster(kullanici)
		case "2":
			sifreDegistir(kullanici)
		case "3":
			logKaydet(fmt.Sprintf("%s çıkış yaptı", kullanici.KullaniciAdi))
			fmt.Println("Çıkış yapılıyor...")
			return
		default:
			fmt.Println("Geçersiz seçim")
		}
	}
}

// Müşteri ekleme fonksiyonu
func musteriEkle() {
	reader := bufio.NewReader(os.Stdin)
	fmt.Print("Yeni müşteri kullanıcı adını girin: ")
	kullaniciAdi, _ := reader.ReadString('\n')
	kullaniciAdi = strings.TrimSpace(kullaniciAdi)

	fmt.Print("Yeni müşteri şifresini girin: ")
	sifre, _ := reader.ReadString('\n')
	sifre = strings.TrimSpace(sifre)

	kullanicilar = append(kullanicilar, Kullanici{KullaniciAdi: kullaniciAdi, Sifre: sifre, Tip: "musteri"})
	logKaydet(fmt.Sprintf("Yeni müşteri %s eklendi", kullaniciAdi))
	fmt.Println("Müşteri başarıyla eklendi")
}
 
func musteriSil() {
	reader := bufio.NewReader(os.Stdin)
	fmt.Print("Silmek istediğiniz müşteri adını girin: ")
	kullaniciAdi, _ := reader.ReadString('\n')
	kullaniciAdi = strings.TrimSpace(kullaniciAdi)

	for i, kullanici := range kullanicilar {
		if kullanici.KullaniciAdi == kullaniciAdi && kullanici.Tip == "musteri" {
			kullanicilar = append(kullanicilar[:i], kullanicilar[i+1:]...)
			logKaydet(fmt.Sprintf("Müşteri %s silindi", kullaniciAdi))
			fmt.Println("Müşteri başarıyla silindi")
			return
		}
	}
	fmt.Println("Müşteri bulunamadı")
}
 
func loglariGoster() {
	dosya, err := os.Open("log.txt")
	if err != nil {
		fmt.Println("Log dosyası okunamadı:", err)
		return
	}
	defer dosya.Close()

	scanner := bufio.NewScanner(dosya)
	for scanner.Scan() {
		fmt.Println(scanner.Text())
	}
}

 
func main() {
	for {
		kullanici := dogrulama()
		if kullanici == nil {
			continue
		}

		if kullanici.Tip == "admin" {
			adminMenu(kullanici)
		} else if kullanici.Tip == "musteri" {
			musteriMenu(kullanici)
		}
	}
}
