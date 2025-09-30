(function ($) {
    $(document).ready(function () {
      const socialMediaShareUrls = {
        facebook: "https://www.facebook.com/sharer/sharer.php?u=",
        twitter: "https://twitter.com/intent/tweet?url=",
        pinterest: "https://www.pinterest.com/pin/create/button/?url=",
        linkedin: "https://www.linkedin.com/shareArticle?url=",
        telegram: "https://t.me/share/url?url=",
        whatsapp: "https://api.whatsapp.com/send?text=",
        email: "mailto:?body=",
        xing: "https://www.xing.com/app/user?op=share&url=",
        tumblr: "https://www.tumblr.com/widgets/share/tool?canonicalUrl=",
        skype: "https://web.skype.com/share?url=",
      };
  
      $(".budi-share-buttons__item").on("click", function () {
        const platform = $(this).data("network");
        const currentPageURL = encodeURIComponent(window.location.href);
        const shareURL = socialMediaShareUrls[platform];

        console.log(currentPageURL);
  
        if (shareURL) {
          if (platform === "email") {
            // For mailto, create the link and trigger it without opening a new window
            const mailtoLink = shareURL + currentPageURL;
            window.location.href = mailtoLink;
          } else {
            const windowWidth = 600;
            const windowHeight = 400;
            const windowLeft = (window.screen.width - windowWidth) / 2;
            const windowTop = (window.screen.height - windowHeight) / 2;
  
            window.open(
              shareURL + currentPageURL,
              "Share",
              `width=${windowWidth}, height=${windowHeight}, left=${windowLeft}, top=${windowTop}`
            );
          }
        }
      });
    });
  })(jQuery);
  