  var reset_disqus = function(){
    DISQUS.reset({
      reload: true,
      config: function () {  
        this.page.identifier = '{{ article.url }}';
        this.page.url = '{{ SITEURL }}/#!{{ article.url }}';
        this.page.title = "{{ article.title }}";
      }
    });
  };
  window.onresize = function() { reset_disqus(); };