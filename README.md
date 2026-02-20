# szpontando

Funkcje w systemie:
1.Grupa funkcji - użytkownik podstawowe
-T-"kry-sign_up"użytkownik może dokonać rejestracji
-użytkownik może potwierdzić rejestrację przez email
-T-"kry-login"-użytkownik może się zalogować (mail i hasło)
-użytkownik może zmienić swoją nazwę, email, hasło (te dane są unikatowe)
-użytkownik może zresetować hasło przez email
2.Grupa funkcji - użytkownik panel
-użytkownik może dodać ogłoszenie-ofertę na sprzątanie (lokalizacja, cena, co sprzątać->kilka opcji checkbox, opis, czas ważności)
-użytkownik może zgłosić się do realizacji oferty (nie można zgłosić się do swojej oferty)
-użytkownik widzi listę użytkowników chcących wykonać usługę i może wybrać wykonawcę
-użytkownik widzi listę użytkowników chcących wykonać usługę, po kliknięciu UŻ może zobaczyć szczegóły (średnia ocena, ostanie zlecenia)
-po wybraniu wykonawcy jest możliwa ocena pracy (ocena słowna i opis 255 znaków max)
-istnieje widok na którym UŻ widzi do jakich ogłoszeń się zgłosił i gdzie został wybrany
-istnieją widoki z ogłoszeniami aktywnymi, zakończonymi, zbanowanymi
-UŻ może edytować wszystkie dane aktywnej oferty
3.Grupa funkcji - strona internetowa
-na stronie głównej prezentowane są najnowsze oferty na sprzątanie
-można wyszukiwać oferty po rodzaju sprzątania (umyj auto, odkurzanie, zamiatanie, mycie okien itd.)
-można wyszukiwać oferty po cenie zlecenia
-można wyszukiwać oferty po lokalizacji (wystarczy nazwa miasta)
-możliwe jest wyszukiwanie wg 3 powyższych kryteriów niezależnie
-po wybraniu ogłoszenia prezentowana jest strona ogłoszenia, jeżeli UŻ jest zalogowany może się zgłosić do wykonania
-istnieje strona na której jest ranking najlepszych wykonawców bazujący na średniej ocen
-na stronie ogłoszenia UŻ może zgłosić ogłoszenie, musi być zalogowany
3.Grupa funkcji - admin
-admin może banować ogłoszenia z widoku ogłoszenia 
-istnieje widok z UŻ o niskich ocenach, admin może nałożyć czasowy ban na wybranych 
-istnieje widok w którym admin widzi ogłosznia zgłoszone jako nieodpowiednie i może wykonać ban / jest ok
-istnieje widok w którym admin wyszukuje UŻ po nazwie / id i widzi jego statystyki: od kiedy konto, ogłoszenia ilościowo/jakościowo, ocena UŻ

Jak zad wykonane to pisz NP. -T-nazwa_brancha-użytkownik może dokonać rejestracji

!!!!!!!!!!!!Nazwa bazy danych to -"sprzantando"- ja nie zrobisz takiej to ci nie zadziała!!!!!!!!!

19.02.26
kry-logowanie
bor-email-aktywacje
max-tapczan_wygląd
tym-strona_glowna uklad

w sql musi być nick w profilu dla sprawdzenia czy wszystko sie dobrze zrobiło jebac nadmiarowość danych
