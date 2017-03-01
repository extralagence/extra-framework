# Extra Framework

## Changelog

###2017/02/21
*Version 0.4.0*

See https://github.com/extralagence/extra-framework/releases/tag/0.4.0 for details.

###2017/01/25
*Version 0.3.1*

See https://github.com/extralagence/extra-framework/releases/tag/0.3.1 for details.

###2017/01/10
*Version 0.2.0*

See https://github.com/extralagence/extra-framework/releases/tag/0.2.0 for details.

###2016/12/21
*Version 0.1.0*

See https://github.com/extralagence/extra-framework/releases/tag/0.1.0 for details.

###2016/10/24
*Updated link field metabox*

Subfields now stored with an array

Needs to update the metabox in the page on existing projects

Structure details :
```php
array (
  'type', // manual content or taxonomy
  'url', // url content if type is manuel
  'content_search', // content search if type is content (for admin use only)
  'post_id', // post id if type is content
  'taxonomy_search', // taxonomy search if type is taxonomy (for admin use only)
  'taxonomy', // taxonomy if type is taxonomy
  'term_slug', // term slug if type is taxonomy
  'title',
  'target'
);
```

###2016/09/27
*Updated the menu page template*
- Hooks have been edited or added

###2016/07/25
*Updated the menu page template*
- Hooks have been edited or added

###2016/06/20
*Updated to jQuery 3.0.0*
- https://jquery.com/upgrade-guide/3.0/#summary-of-important-changes


*JS trigger events in extra slider*
- renamed extra:slider:pause to extra:slider:paused
- renamed extra:slider:resume to extra:slider:resumed

###2016/06/14
*JS trigger events in extra slider*
- renamed update to extra:slider:update
- renamed next to extra:slider:next
- renamed prev to extra:slider:prev
- renamed goto to extra:slider:goto
- renamed pause to extra:slider:pause
- renamed resume to extra:slider:resume

###2016/03/22
*JS Events*
- renamed extra.resize to extra:resize
- renamed extra.responsive-resize to extra:resize:responsive
- renamed complete.extra.responsiveImage to extra:responsiveImage:load
- renamed complete.extra.responsiveImageTotal to extra.responsiveImage:complete
- renamed extra.responsiveImage to extra:responsiveImage:init
- renamed extra.initFancybox to extra:fancybox:init

###2016/03/21
*MODULES*
- removed fancyselect
- removed extra.checkbox
- removed jquery.gsap
- removed jquery.fracs

###2016/03/16
*MODULES*
- removed extra language switcher

## How-to

Drop it in a theme.

Be sure to have a folder named "extra" at the root level of the theme, aside of the extra-framework

It must contains this folders :

* extra/
* extra/modules
* extra/includes
* extra/setup

That's all folks !

###SNIPPETS
[Extra Snippets](https://github.com/extralagence/extra-framework/blob/master/snippets.md)
