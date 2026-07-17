/* Reading progress bar + fade-in on scroll */
(function () {
  "use strict";

  // Reading progress bar (inject once).
  if (!document.querySelector(".khas-progress") && document.querySelector(".khas-article")) {
    var bar = document.createElement("div");
    bar.className = "khas-progress";
    bar.innerHTML = '<div class="khas-progress__bar"></div>';
    document.body.appendChild(bar);

    var fill = bar.querySelector(".khas-progress__bar");
    var article = document.querySelector(".khas-article");

    function update() {
      if (!article || !fill) return;
      var rect = article.getBoundingClientRect();
      var winH = window.innerHeight || document.documentElement.clientHeight;
      var docTop = window.pageYOffset || document.documentElement.scrollTop;
      var top = rect.top + docTop;
      var height = rect.height;
      var scrolled = docTop + winH * 0.2 - top;
      var pct = Math.max(0, Math.min(100, (scrolled / height) * 100));
      fill.style.width = pct + "%";
    }
    var ticking = false;
    window.addEventListener(
      "scroll",
      function () {
        if (!ticking) {
          window.requestAnimationFrame(function () {
            update();
            ticking = false;
          });
          ticking = true;
        }
      },
      { passive: true }
    );
    window.addEventListener("resize", update);
    update();
  }

  // Fade-in on scroll (IntersectionObserver).
  var faders = document.querySelectorAll(
    ".khas-fade, .khas-feature-big, .khas-feature-side, .khas-latest-card, .khas-card, .khas-popcat, .khas-author, .khas-pick, .khas-news"
  );
  if ("IntersectionObserver" in window && faders.length) {
    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) {
            e.target.classList.add("is-visible");
            io.unobserve(e.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: "0px 0px -40px 0px" }
    );
    faders.forEach(function (el, i) {
      el.classList.add("khas-fade");
      el.style.transitionDelay = Math.min(i * 60, 240) + "ms";
      io.observe(el);
    });
  }
})();
