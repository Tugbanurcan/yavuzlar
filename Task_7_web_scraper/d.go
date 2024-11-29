package main

import (
	"bufio"
	"fmt"
	"log"
	"net/http"
	"os"
	"strings"
	"github.com/PuerkitoBio/goquery"
)

 
func fetchHackerNews() {
	 
	const url = "https://thehackernews.com/"
 
	res, err := http.Get(url)
	if err != nil {
		log.Fatalf("Hata: %v", err)
	}
	defer res.Body.Close()

	 
	if res.StatusCode != 200 {
		log.Fatalf("HTTP Hatası: %d %s", res.StatusCode, res.Status)
	}

 
	doc, err := goquery.NewDocumentFromReader(res.Body)
	if err != nil {
		log.Fatalf("Veri işleme hatası: %v", err)
	}

 
	file, err := os.Create("hackernews_data.txt")
	if err != nil {
		log.Fatalf("Dosya oluşturulamadı: %v", err)
	}
	defer file.Close()

	 
	doc.Find("div.body-post").Each(func(i int, s *goquery.Selection) {
 
		title := s.Find("h2.home-title").Text()

		 
		description := s.Find("div.home-desc").Text()

		 
		date := s.Find("span.h-datetime").Text() 

		 
		if strings.TrimSpace(title) == "" {
			title = "Başlık bulunamadı"
		}
 
		if strings.TrimSpace(date) == "" {
			date = "Tarih bulunamadı"
		}
 
		_, err := file.WriteString(fmt.Sprintf("Başlık: %s\nAçıklama: %s\nTarih: %s\n\n", title, description, date))
		if err != nil {
			log.Fatalf("Dosyaya yazma hatası: %v", err)
		}
	})

	fmt.Println("Veriler hackernews_data.txt dosyasına kaydedildi.")
}

func fetchAllNetflixMovies() {
	 
	file, err := os.Create("netflix_movies_all_pages.txt")
	if err != nil {
		log.Fatalf("Dosya oluşturulamadı: %v", err)
	}
	defer file.Close()

	totalMovies := 0 

	 
	for page := 1; page <= 145; page++ {
		 
		url := fmt.Sprintf("https://www.sinemalar.com/platformlar/netflix/filmler?page=%d", page)
 
		res, err := http.Get(url)
		if err != nil {
			log.Printf("HTTP isteği başarısız (Sayfa %d): %v", page, err)
			continue
		}
		defer res.Body.Close()

	 
		if res.StatusCode != 200 {
			log.Printf("HTTP hata kodu (Sayfa %d): %d %s", page, res.StatusCode, res.Status)
			continue
		}
 
		doc, err := goquery.NewDocumentFromReader(res.Body)
		if err != nil {
			log.Printf("HTML belgesi işlenemedi (Sayfa %d): %v", page, err)
			continue
		}

		 
		doc.Find(".movies a").Each(func(i int, s *goquery.Selection) {
			 
			title := s.Find(".title").Text()
			 
			link, exists := s.Attr("href")
			if exists {
				 
				_, err := file.WriteString(
					title + "\nhttps://www.sinemalar.com" + link + "\n\n")
				if err != nil {
					log.Fatalf("Dosyaya yazma hatası (Sayfa %d): %v", page, err)
				}
				totalMovies++  
			}
		})
		fmt.Printf("Sayfa %d tamamlandı.\n", page)
	}
 
	fmt.Printf("Toplam %d en iyi netflix filmleri bulundu ve kaydedildi.\n", totalMovies)
	_, err = file.WriteString(fmt.Sprintf("Toplam film sayısı: %d\n", totalMovies))
	if err != nil {
		log.Fatalf("Toplam film sayısı dosyaya yazılamadı: %v", err)
	}
}

func fetchTrendGitHub(){
	
 
	res, err := http.Get("https://github.com/trending")
	if err != nil {
		fmt.Println("HTTP isteği başarısız oldu:", err)
		return
	}
	defer res.Body.Close()  

	 
	if res.StatusCode != 200 {
		fmt.Println("Hata", res.StatusCode)
		return
	}

	 
	doc, err := goquery.NewDocumentFromReader(res.Body)
	if err != nil {
		fmt.Println("goquery hatası:", err)
		return
	}

	 
	file, err := os.Create("trending_github_repos.txt")
	if err != nil {
		fmt.Println("Dosya oluşturulamadı:", err)
		return
	}
	defer file.Close()  
 
	doc.Find("article.Box-row").Each(func(i int, selection *goquery.Selection) {
		 
		title := strings.TrimSpace(selection.Find(".h3.lh-condensed a").Text())
 
		description := strings.TrimSpace(selection.Find("p.col-9.color-fg-muted.my-1.pr-4").Text())

		 
		language := strings.TrimSpace(selection.Find(".f6.color-fg-muted.mt-2 span[itemprop='programmingLanguage']").Text())

		 
		if language == "" {
			language = "Dil bulunamadı"
		}
 
		_, err := fmt.Fprintf(file, "Repo Adı: %s\nAçıklama: %s\nDil: %s\n", title, description, language)
		if err != nil {
			fmt.Println("Dosyaya yazarken hata oluştu:", err)
			return
		}

	 
		_, err = file.WriteString("---------------------------------------------------\n")
		if err != nil {
			fmt.Println("Dosyaya satır sonu eklerken hata oluştu:", err)
			return
		}
	})

	fmt.Println("Veriler trending_github_repos.txt dosyasına başarıyla kaydedildi.")

}

 


func main() {
	reader := bufio.NewReader(os.Stdin)

	for {
		fmt.Println("\nMenü:")
		fmt.Println("-1: Hacker News verilerini çek ve kaydet")
		fmt.Println("-2: En iyi netflix filimlerini çek ve kaydet")
		fmt.Println("-3: Trending GitHub Depolarını Çek ve Kaydet")
		fmt.Println("-4: Çıkış")
		fmt.Print("Seçiminizi yapın: ")

		input, _ := reader.ReadString('\n')
		input = strings.TrimSpace(input)

		switch input {
		case "-1":
			fetchHackerNews() 
		case "-2":
			fmt.Println("Tüm Netflix filmleri çekiliyor...")
			fetchAllNetflixMovies()
		case "-3":
			fetchTrendGitHub() 
		case "-4":
			fmt.Println("Çıkış yapılıyor...")
			os.Exit(0)
		default:
			fmt.Println("Geçersiz seçim, lütfen tekrar deneyin.")
		}
	}
}
