# extra.cookies
## HTML Structure
```
<div class="extra-cookies-popup">
	<div class="extra-cookies-popup-inner">
		Acceptez-vous l'utilisation des cookies ?
		<button type="button" class="extra-cookies-button">Accepter</button>
	</div>
</div>
```

## Javascript to call cookie
```
$('.extra-cookies-popup').extraCookies({
	position: 'bottom'
});
```

### Options
__cookieName__ (string)  
default: 'extra_cookies_accepted'  

__cookieValue__ (string)  
default: 'accepted'

__expirationDays__ (int)  
default: 60  (days)

__position__ (string)  
default: 'bottom'  
values: 'bottom', 'top', 'custom'

__waitingBeforeCheck__ (int)  
default: 1000 (ms)

