/**
 * Autotech Website - Main JavaScript
 * Features: Language switcher (VI/EN), Sticky navbar, Smooth scroll,
 *           AOS animation, Mobile menu, Form validation, Back to top
 */

(function () {
  'use strict';

  /* =========================================================================
     1. Language Switcher
     ========================================================================= */
  var currentLang = localStorage.getItem('autotech-lang') || 'vi';

  /**
   * Apply the chosen language to all elements with data-vi / data-en attributes.
   * @param {string} lang - 'vi' or 'en'
   */
  function applyLanguage(lang) {
    currentLang = lang;
    localStorage.setItem('autotech-lang', lang);

    // Update all translatable text nodes
    var elements = document.querySelectorAll('[data-vi][data-en]');
    elements.forEach(function (el) {
      el.textContent = el.getAttribute('data-' + lang);
    });

    // Update all translatable placeholder attributes
    var placeholders = document.querySelectorAll('[data-placeholder-vi][data-placeholder-en]');
    placeholders.forEach(function (el) {
      el.placeholder = el.getAttribute('data-placeholder-' + lang);
    });

    // Update <html> lang attribute for accessibility / SEO
    document.documentElement.lang = lang;

    // Update the language button UI
    var langBtn = document.getElementById('langBtn');
    var langText = document.getElementById('langText');
    if (langBtn && langText) {
      if (lang === 'vi') {
        langBtn.querySelector('.lang-btn__flag').textContent = '🇻🇳';
        langText.textContent = 'VI';
      } else {
        langBtn.querySelector('.lang-btn__flag').textContent = '🇺🇸';
        langText.textContent = 'EN';
      }
    }

    // Update page title and meta description for SEO (page-aware)
    var pageTitles = {
      'inverter.html': {
        vi: 'Biến tần (Inverter) - Autotech',
        en: 'Inverter (VFD) - Autotech'
      },
      'index.html': {
        vi: 'Autotech - Công nghệ Tự động hóa & Biến tần',
        en: 'Autotech - Professional Automation Technology & Inverter Solutions'
      }
    };
    var pageDescs = {
      'inverter.html': {
        vi: 'Autotech cung cấp và lắp đặt biến tần chính hãng EasyDrive — đa dạng model từ 0.4kW đến 500kW, phù hợp mọi ứng dụng công nghiệp',
        en: 'Autotech supplies and installs genuine EasyDrive inverters — wide range of models from 0.4kW to 500kW, suitable for all industrial applications'
      },
      'index.html': {
        vi: 'Autotech - Công ty chuyên cung cấp biến tần, PLC, HMI và giải pháp tự động hóa dây chuyền chuyên nghiệp tại Việt Nam',
        en: 'Autotech - Professional supplier of inverters, PLCs, HMIs and industrial automation solutions in Vietnam'
      }
    };
    var currentPage = window.location.pathname.split('/').pop() || 'index.html';
    var titleMap = pageTitles[currentPage] || pageTitles['index.html'];
    var descMap = pageDescs[currentPage] || pageDescs['index.html'];
    document.title = titleMap[lang];
    var metaDescEl = document.querySelector('meta[name="description"]');
    if (metaDescEl) {
      metaDescEl.setAttribute('content', descMap[lang]);
    }

    // Update form placeholders with translated text
    updateFormPlaceholders(lang);
  }

  /**
   * Update form field placeholders when language changes.
   * @param {string} lang
   */
  function updateFormPlaceholders(lang) {
    var placeholderMap = {
      name:    { vi: 'Nguyễn Văn A', en: 'John Smith' },
      phone:   { vi: '0901 234 567', en: '+84 901 234 567' },
      email:   { vi: 'example@email.com', en: 'example@email.com' },
      message: { vi: 'Nhập nội dung cần tư vấn...', en: 'Enter your message...' }
    };

    Object.keys(placeholderMap).forEach(function (id) {
      var el = document.getElementById(id);
      if (el) {
        el.placeholder = placeholderMap[id][lang];
      }
    });

    // Update select options
    var subjectSelect = document.getElementById('subject');
    if (subjectSelect) {
      var options = subjectSelect.querySelectorAll('option[data-vi][data-en]');
      options.forEach(function (opt) {
        opt.textContent = opt.getAttribute('data-' + lang);
      });
    }
  }

  // Toggle language on button click
  var langBtn = document.getElementById('langBtn');
  if (langBtn) {
    langBtn.addEventListener('click', function () {
      applyLanguage(currentLang === 'vi' ? 'en' : 'vi');
    });
  }

  // Apply stored language on page load
  applyLanguage(currentLang);

  /* =========================================================================
     2. Sticky Navbar + Active Link Highlight
     ========================================================================= */
  var navbar = document.getElementById('navbar');

  function handleNavbarScroll() {
    if (window.scrollY > 20) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  }

  // Highlight active nav link based on scroll position
  function updateActiveLink() {
    var sections = document.querySelectorAll('section[id]');
    var scrollPos = window.scrollY + getNavbarHeight() + 10;

    sections.forEach(function (section) {
      var top    = section.offsetTop;
      var bottom = top + section.offsetHeight;
      var id     = section.getAttribute('id');

      if (scrollPos >= top && scrollPos < bottom) {
        document.querySelectorAll('.navbar__link').forEach(function (link) {
          link.classList.remove('active');
          if (link.getAttribute('href') === '#' + id) {
            link.classList.add('active');
          }
        });
      }
    });
  }

  function getNavbarHeight() {
    return navbar ? navbar.offsetHeight : 70;
  }

  window.addEventListener('scroll', function () {
    handleNavbarScroll();
    updateActiveLink();
    handleBackToTop();
    handleAOS();
  }, { passive: true });

  handleNavbarScroll();

  /* =========================================================================
     3. Mobile Menu (Hamburger)
     ========================================================================= */
  var hamburger  = document.getElementById('hamburger');
  var mobileMenu = document.getElementById('mobileMenu');

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function () {
      var isOpen = hamburger.classList.toggle('active');
      mobileMenu.classList.toggle('open', isOpen);
      hamburger.setAttribute('aria-expanded', String(isOpen));
      mobileMenu.setAttribute('aria-hidden', String(!isOpen));
    });

    // Close mobile menu when a link is clicked
    mobileMenu.querySelectorAll('.mobile-menu__link').forEach(function (link) {
      link.addEventListener('click', function () {
        hamburger.classList.remove('active');
        mobileMenu.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
        mobileMenu.setAttribute('aria-hidden', 'true');
      });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function (e) {
      if (!navbar.contains(e.target)) {
        hamburger.classList.remove('active');
        mobileMenu.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
        mobileMenu.setAttribute('aria-hidden', 'true');
      }
    });
  }

  /* =========================================================================
     4. Smooth Scroll (for browsers that don't support scroll-behavior: smooth)
     ========================================================================= */
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var targetId = this.getAttribute('href');
      if (targetId === '#') return;

      var target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        var offset = getNavbarHeight() + 8;
        var top    = target.getBoundingClientRect().top + window.scrollY - offset;

        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

  /* =========================================================================
     5. AOS — Animate On Scroll (lightweight, no external lib)
     ========================================================================= */
  var aosElements = document.querySelectorAll('.aos-fade-up, .aos-fade-right, .aos-fade-left');

  function handleAOS() {
    var windowHeight = window.innerHeight;

    aosElements.forEach(function (el) {
      var rect    = el.getBoundingClientRect();
      var visible = rect.top < windowHeight - 80;

      if (visible) {
        var delay = el.style.getPropertyValue('--delay') || '0s';
        // Parse delay for staggered animation
        var delayMs = parseFloat(delay) * 1000;

        if (!el.dataset.aosTriggered) {
          el.dataset.aosTriggered = 'true';
          setTimeout(function () {
            el.classList.add('aos-animate');
          }, delayMs);
        }
      }
    });
  }

  // Run once on load
  window.addEventListener('load', function () {
    handleAOS();
  });

  /* =========================================================================
     6. Back to Top Button
     ========================================================================= */
  var backToTopBtn = document.getElementById('backToTop');

  function handleBackToTop() {
    if (!backToTopBtn) return;
    if (window.scrollY > 400) {
      backToTopBtn.classList.add('visible');
    } else {
      backToTopBtn.classList.remove('visible');
    }
  }

  if (backToTopBtn) {
    backToTopBtn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* =========================================================================
     7. Contact Form Validation
     ========================================================================= */
  var contactForm = document.getElementById('contactForm');

  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();

      if (validateForm()) {
        showFormSuccess();
      }
    });

    // Real-time validation on blur
    ['name', 'phone', 'email', 'message'].forEach(function (fieldId) {
      var field = document.getElementById(fieldId);
      if (field) {
        field.addEventListener('blur', function () {
          validateField(fieldId);
        });
        field.addEventListener('input', function () {
          // Clear error when user starts typing
          clearFieldError(fieldId);
        });
      }
    });
  }

  /**
   * Validate all form fields.
   * @returns {boolean} true if all fields are valid
   */
  function validateForm() {
    var nameValid    = validateField('name');
    var phoneValid   = validateField('phone');
    var emailValid   = validateField('email');
    var messageValid = validateField('message');

    return nameValid && phoneValid && emailValid && messageValid;
  }

  /**
   * Validate a single field and show/clear its error.
   * @param {string} fieldId
   * @returns {boolean}
   */
  function validateField(fieldId) {
    var field    = document.getElementById(fieldId);
    var errorEl  = document.getElementById(fieldId + 'Error');
    if (!field || !errorEl) return true;

    var value   = field.value.trim();
    var isValid = true;
    var message = '';

    var labels = {
      vi: {
        name:    { required: 'Vui lòng nhập họ và tên.', minLen: 'Họ tên phải có ít nhất 2 ký tự.' },
        phone:   { required: 'Vui lòng nhập số điện thoại.', invalid: 'Số điện thoại không hợp lệ.' },
        email:   { required: 'Vui lòng nhập email.', invalid: 'Địa chỉ email không hợp lệ.' },
        message: { required: 'Vui lòng nhập nội dung.', minLen: 'Nội dung phải có ít nhất 10 ký tự.' }
      },
      en: {
        name:    { required: 'Please enter your full name.', minLen: 'Name must be at least 2 characters.' },
        phone:   { required: 'Please enter your phone number.', invalid: 'Invalid phone number.' },
        email:   { required: 'Please enter your email.', invalid: 'Invalid email address.' },
        message: { required: 'Please enter your message.', minLen: 'Message must be at least 10 characters.' }
      }
    };

    var lang   = currentLang;
    var msgs   = labels[lang] || labels['vi'];

    switch (fieldId) {
      case 'name':
        if (!value) {
          isValid = false; message = msgs.name.required;
        } else if (value.length < 2) {
          isValid = false; message = msgs.name.minLen;
        }
        break;

      case 'phone':
        if (!value) {
          isValid = false; message = msgs.phone.required;
        } else if (!/^[0-9+\s\-().]{7,15}$/.test(value)) {
          isValid = false; message = msgs.phone.invalid;
        }
        break;

      case 'email':
        if (!value) {
          isValid = false; message = msgs.email.required;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
          isValid = false; message = msgs.email.invalid;
        }
        break;

      case 'message':
        if (!value) {
          isValid = false; message = msgs.message.required;
        } else if (value.length < 10) {
          isValid = false; message = msgs.message.minLen;
        }
        break;
    }

    if (isValid) {
      field.classList.remove('error');
      errorEl.textContent = '';
    } else {
      field.classList.add('error');
      errorEl.textContent = message;
    }

    return isValid;
  }

  /**
   * Clear error state for a field.
   * @param {string} fieldId
   */
  function clearFieldError(fieldId) {
    var field   = document.getElementById(fieldId);
    var errorEl = document.getElementById(fieldId + 'Error');
    if (field) field.classList.remove('error');
    if (errorEl) errorEl.textContent = '';
  }

  /**
   * Show success message and reset form after a short delay.
   */
  function showFormSuccess() {
    var successEl = document.getElementById('formSuccess');
    var submitBtn = document.getElementById('submitBtn');

    if (successEl) {
      successEl.classList.add('show');
    }
    if (submitBtn) {
      submitBtn.disabled = true;
    }

    // Reset form after 4 seconds
    setTimeout(function () {
      if (contactForm) contactForm.reset();
      if (successEl) successEl.classList.remove('show');
      if (submitBtn) submitBtn.disabled = false;
    }, 4000);
  }

  /* =========================================================================
     8. Initial AOS trigger (for elements already in viewport on page load)
     ========================================================================= */
  setTimeout(handleAOS, 100);

  /* =========================================================================
     9. EasyDrive accordion (Inverter card)
     ========================================================================= */
  var accordionToggle = document.querySelector('.easydrive-accordion__toggle');
  var accordionBody   = document.getElementById('easydrive-models');

  if (accordionToggle && accordionBody) {
    accordionToggle.addEventListener('click', function () {
      var isOpen = accordionToggle.getAttribute('aria-expanded') === 'true';
      var span   = accordionToggle.querySelector('span');

      if (isOpen) {
        accordionToggle.setAttribute('aria-expanded', 'false');
        accordionBody.hidden = true;
        if (span) {
          span.setAttribute('data-vi', 'Xem danh sách model');
          span.setAttribute('data-en', 'View Model List');
          span.textContent = document.documentElement.lang === 'en'
            ? 'View Model List'
            : 'Xem danh sách model';
        }
      } else {
        accordionToggle.setAttribute('aria-expanded', 'true');
        accordionBody.hidden = false;
        if (span) {
          span.setAttribute('data-vi', 'Thu gọn');
          span.setAttribute('data-en', 'Collapse');
          span.textContent = document.documentElement.lang === 'en'
            ? 'Collapse'
            : 'Thu gọn';
        }
      }
    });
  }

  // -------------------------------------------------------------------------
  //  10. FAQ accordion
  // -------------------------------------------------------------------------
  var faqQuestions = document.querySelectorAll('.faq__question');
  faqQuestions.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var answer = btn.parentElement.querySelector('.faq__answer');
      var isOpen = btn.getAttribute('aria-expanded') === 'true';
      // Close all other open items
      faqQuestions.forEach(function (other) {
        if (other !== btn && other.getAttribute('aria-expanded') === 'true') {
          other.setAttribute('aria-expanded', 'false');
          other.parentElement.querySelector('.faq__answer').hidden = true;
        }
      });
      if (isOpen) {
        btn.setAttribute('aria-expanded', 'false');
        answer.hidden = true;
      } else {
        btn.setAttribute('aria-expanded', 'true');
        answer.hidden = false;
      }
    });
  });

})();
