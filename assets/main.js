(function () {
  "use strict";

  // Mobile menu toggle
  var burger = document.querySelector(".khas-burger");
  var mnav = document.querySelector(".khas-mobile-nav");
  if (burger && mnav) {
    burger.addEventListener("click", function () {
      mnav.classList.toggle("open");
    });
  }

  // Newsletter AJAX
  var form = document.querySelector(".khas-news__form");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      var input = form.querySelector("input[type='email']");
      var btn = form.querySelector("button");
      var msg = document.querySelector(".khas-news__msg");
      if (!input || !window.KHAS) return;

      var original = btn.textContent;
      btn.textContent = "در حال ثبت…";
      btn.disabled = true;
      if (msg) msg.textContent = "";

      var body = new URLSearchParams();
      body.append("action", "khas_subscribe");
      body.append("nonce", window.KHAS.nonce);
      body.append("email", input.value);

      fetch(window.KHAS.ajaxUrl, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: body.toString(),
      })
        .then(function (r) {
          return r.json();
        })
        .then(function (res) {
          if (msg) {
            msg.textContent = (res.data && res.data.message) || "انجام شد.";
            msg.style.color = res.success ? "#fff" : "#ffe0e0";
          }
          if (res.success) input.value = "";
        })
        .catch(function () {
          if (msg) {
            msg.textContent = "ارتباط با سرور برقرار نشد.";
            msg.style.color = "#ffe0e0";
          }
        })
        .finally(function () {
          btn.textContent = original;
          btn.disabled = false;
        });
    });
  }
})();
