# extra.ajaxnavigation
## HTML Structure
```
<div class="extra-ajax-navigation-wrapper">
 
    <a class="extra-ajax-navigation-previous-button" data-default-text="" data-finish-text="" href=""></a>
  
    <div class="post-list extra-ajax-navigation-list"> 
      
        <span class="extra-ajax-navigation-page-marker" data-page-num="" data-page-permalink="" data-page-title=""></span>
          
        <article class="extra-ajax-navigation-item"></article>
          
    </div>
  
    <a class="extra-ajax-navigation-next-button" data-default-text="" data-finish-text="" href=""></a>
  
</div>
```

## Javascript to call ajax pagination
```
new ExtraAjaxNavigation({});
```

### Options
__wrapper__ (string)  
default: null  

__contentSelector__ (string)  
default: null  

__listSelector__ (string)  
default: null  

__itemSelector__ (string)  
default: null  

__nextButtonSelector__ (string)  
default: null  

__previousButtonSelector__ (string)  
default: null  

__loadingClass__ (string)  
default: null  

__nextCompleteClass__ (string)  
default: null  

__previousCompleteClass__ (string)  
default: null  

__noMoreLinkClass__ (string)  
default: null  

__pageMarkerSelector__ (string)  
default: null  

__startPageAt__ (int)  
default: null  


### Events

__beforeAddItems__  
.on("extra:ajaxNavigation:beforeAddItems", function (event, isPrevious, $items) {}); 

__afterAddItems__  
.on("extra:ajaxNavigation:afterAddItems", function (event, isPrevious, $items) {}); 

__previousComplete__  
.on("extra:ajaxNavigation:previousComplete", function () {}); 

__nextComplete__  
.on("extra:ajaxNavigation:nextComplete", function () {}); 
