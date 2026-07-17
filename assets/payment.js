(function () {
  "use strict";

  if (!window.KHAS_PAY) return;

  var ajaxUrl = window.KHAS_PAY.ajaxUrl;
  var nonce = window.KHAS_PAY.nonce;

  function post(action, data) {
    var body = new URLSearchParams();
    body.append("action", action);
    body.append("nonce", nonce);
    Object.keys(data || {}).forEach(function (k) {
      body.append(k, data[k]);
    });
    return fetch(ajaxUrl, {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: body.toString(),
    }).then(function (r) {
      return r.json();
    });
  }

  function faNum(n) {
    try {
      return new Intl.NumberFormat("fa-IR").format(n);
    } catch (e) {
      return String(n);
    }
  }

  /* ---------- Membership checkout ---------- */
  var form = document.getElementById("khas-checkout-form");
  if (form) {
    var amountEl = document.getElementById("khas-checkout-amount");
    var errorEl = document.getElementById("khas-checkout-error");
    var submitBtn = document.getElementById("khas-checkout-submit");
    var planPrices = {};

    form.querySelectorAll('input[name="plan_slug"]').forEach(function (radio) {
      var label = radio.closest("label");
      if (label) {
        var em = label.querySelector("em");
        if (em) {
          // Keep a data-amount on the radio if present via sibling text is hard;
          // we'll recompute from known map set below via data attributes.
        }
      }
    });

    // Build price map from pill text is fragile — use amount buttons data-plan on plan cards.
    document.querySelectorAll(".khas-plan-card .khas-btn[data-plan]").forEach(function (btn) {
      var slug = btn.getAttribute("data-plan");
      var card = btn.closest(".khas-plan-card");
      if (!card) return;
      var num = card.querySelector(".price .num");
      if (num) {
        // Store raw latin digits if we can parse from radio value later via known mapping in DOM.
        planPrices[slug] = num.textContent.trim();
      }
      btn.addEventListener("click", function (e) {
        e.preventDefault();
        var radio = form.querySelector('input[name="plan_slug"][value="' + slug + '"]');
        if (radio) {
          radio.checked = true;
          radio.dispatchEvent(new Event("change", { bubbles: true }));
        }
        var checkout = document.getElementById("khas-checkout");
        if (checkout) checkout.scrollIntoView({ behavior: "smooth", block: "start" });
      });
    });

    // Known amounts (must match PHP plans)
    var AMOUNTS = { monthly: 99000, quarterly: 249000, yearly: 790000 };

    function updateAmount() {
      var selected = form.querySelector('input[name="plan_slug"]:checked');
      if (!selected || !amountEl) return;
      var amount = AMOUNTS[selected.value] || 0;
      amountEl.textContent = faNum(amount) + " تومان";
    }

    form.querySelectorAll('input[name="plan_slug"]').forEach(function (r) {
      r.addEventListener("change", updateAmount);
    });
    updateAmount();

    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (errorEl) {
        errorEl.hidden = true;
        errorEl.textContent = "";
      }
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = "در حال انتقال به درگاه…";
      }

      var selected = form.querySelector('input[name="plan_slug"]:checked');
      post("khas_payment_initiate", {
        plan_slug: selected ? selected.value : "",
        full_name: (form.querySelector('[name="full_name"]') || {}).value || "",
        email: (form.querySelector('[name="email"]') || {}).value || "",
        mobile: (form.querySelector('[name="mobile"]') || {}).value || "",
      })
        .then(function (res) {
          if (res && res.success && res.data && res.data.redirect_url) {
            window.location.href = res.data.redirect_url;
            return;
          }
          var msg =
            (res && res.data && res.data.message) ||
            "خطا در ایجاد تراکنش. دوباره تلاش کنید.";
          if (errorEl) {
            errorEl.hidden = false;
            errorEl.textContent = msg;
          }
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = "پرداخت و فعال‌سازی اشتراک";
          }
        })
        .catch(function () {
          if (errorEl) {
            errorEl.hidden = false;
            errorEl.textContent = "ارتباط با سرور برقرار نشد.";
          }
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = "پرداخت و فعال‌سازی اشتراک";
          }
        });
    });
  }

  /* ---------- Gateway simulator ---------- */
  var gateway = document.querySelector(".khas-gateway");
  if (gateway) {
    var token = gateway.getAttribute("data-token");
    var cardInput = document.getElementById("khas-card");
    var cvv2Input = document.getElementById("khas-cvv2");
    var expMonth = document.getElementById("khas-exp-month");
    var expYear = document.getElementById("khas-exp-year");
    var pinInput = document.getElementById("khas-pin");
    var errEl = document.getElementById("khas-gateway-error");
    var payBtn = document.getElementById("khas-pay-btn");
    var cancelBtn = document.getElementById("khas-cancel-btn");
    var timerEl = document.getElementById("khas-gateway-timer");
    var gatewayForm = document.getElementById("khas-gateway-form");
    var seconds = 600;

    if (timerEl) {
      setInterval(function () {
        if (seconds > 0) seconds -= 1;
        var mm = String(Math.floor(seconds / 60)).padStart(2, "0");
        var ss = String(seconds % 60).padStart(2, "0");
        timerEl.textContent = mm + ":" + ss;
        if (seconds < 60) timerEl.style.color = "#dc2626";
      }, 1000);
    }

    if (cardInput) {
      cardInput.addEventListener("input", function () {
        var digits = cardInput.value.replace(/\D/g, "").slice(0, 16);
        cardInput.value = digits.replace(/(.{4})/g, "$1 ").trim();
      });
    }

    function showError(msg) {
      if (!errEl) return;
      errEl.hidden = false;
      errEl.textContent = msg;
    }

    function finalize(actionType) {
      if (payBtn) payBtn.disabled = true;
      if (cancelBtn) cancelBtn.disabled = true;
      if (payBtn && actionType === "pay") payBtn.textContent = "در حال پرداخت…";

      var last4 = (cardInput ? cardInput.value.replace(/\D/g, "") : "0000").slice(-4);
      post("khas_payment_verify", {
        token: token,
        action_type: actionType,
        card_last4: last4,
      })
        .then(function () {
          // Always go to result page — server has final status.
          window.location.href = "/payment/result/" + encodeURIComponent(token) + "/";
        })
        .catch(function () {
          showError("خطا در پردازش پرداخت.");
          if (payBtn) {
            payBtn.disabled = false;
            payBtn.textContent = "پرداخت";
          }
          if (cancelBtn) cancelBtn.disabled = false;
        });
    }

    if (gatewayForm) {
      gatewayForm.addEventListener("submit", function (e) {
        e.preventDefault();
        var digits = cardInput ? cardInput.value.replace(/\D/g, "") : "";
        if (digits.length !== 16) return showError("شماره کارت باید ۱۶ رقم باشد.");
        if (!cvv2Input || cvv2Input.value.length < 3) return showError("کد CVV2 نامعتبر است.");
        if (!expMonth || !expYear || !expMonth.value || !expYear.value) {
          return showError("تاریخ انقضای کارت را وارد کنید.");
        }
        if (!pinInput || pinInput.value.length < 4) {
          return showError("رمز دوم (رمز پویا) را وارد کنید.");
        }
        finalize("pay");
      });
    }

    if (cancelBtn) {
      cancelBtn.addEventListener("click", function () {
        finalize("cancel");
      });
    }

    var otpBtn = document.getElementById("khas-otp-btn");
    if (otpBtn) {
      otpBtn.addEventListener("click", function () {
        otpBtn.disabled = true;
        otpBtn.textContent = "ارسال شد";
        setTimeout(function () {
          otpBtn.disabled = false;
          otpBtn.textContent = "دریافت رمز";
        }, 3000);
      });
    }
  }
})();
