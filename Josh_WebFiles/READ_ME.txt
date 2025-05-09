Questa cartella contiene esclusivamente file che ho creato per fare pratica nella programmazione
WEB.
ATTENZIONE: 
non avendo il database MySQL questo sito non funziona (almeno per le query)

Struttura del sito:
		  ______________________home.php______________________

		/                           |                           \
          Database_test.php          Design_test.php               admin_login.php
           /             \
    sign_up.php        user_page.php



- home.php: La pagina iniziale, che contiene le principali sotto pagine in cui testo cose specifiche

- Database_test.php: qui è dove testo query e altre funzione, tutte ricavate dalla
mia Library chaiamta "database_functions.php"

- database_functions.php: è la libreria in cui tengo tutte le funzioni che utilizzo, contiene
utilità come:
	- getInput  (prende input del utente, e lo pulisce tramite checkEmail o altro)
	- getUserdata (ritorna un array che contiene 2 o più colonne di un utente)
	- getUserField (ritorna un unico valore di una colonna di un utente)
	- verifyUser (controllo del login di un utente)
	- addUser
	- getAdminData
	- getAdminField
	- verifyAdmin (controllo del login per un admin)
	- addAdmin
	- checkEmail (pulisce l'email da simboli pericolsi che possono causare SQLinjection)
	- checkPass

- sign_up.php: semplice pagina di sign up di un utente, richiede le info, le controlla e
	       le mette nel database

- user_page.php: pagina minimale, serve solo a mostrare il successo di un log in
		 e a dimostrare che i dati della sessione sono rimasti salvati e usabili
	         infatti, la pagina stamperà le informazioni del utente che ha fatto log in
	         dimostrando che la pagina ricorda chi è l'utente.

- Design_test.php: non contiene nessuna funzionalità, è solo per testare codice CSS.

- admin_login.php: pagina per il login in di un admin, è identica a quella di utente ma usa un altra tabella



  