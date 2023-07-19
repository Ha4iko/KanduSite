'use strict';
$(window).on('load', function () {
  window.selectedElements = [
    '.section',
    '.nav',
    '.filter',
    '.table',
    '.add-table',
    '.a-panel',
    '.popup-content',
    '.add-table',
  ];

  $('html').addClass('load');

  dropdown();
  dropdownPosition();

  video();
  field();

  tabsEvents();
  tabsInit();

  fittext();
  scheduleSlider();
  datepicker();
  popup();
  checkbox();
  cellEdit();
  controlFile();
  password();
  groupScoreEdit();
  sortStates();

  selectEvents();
  selectInit();

  autocomplete();
  profileImg();
  brackets();
  fullscreen();
  headerMenu();
  scroll();
  chart();
  tableSort();
  headerFixed();

  showItems();
  groupDublicate();
  append();

  $(window).on('scroll', function (e) {
    dropdownPosition();
    headerFixed();
  });

  $(window).on('resize', function () {
    dropdownPosition();
    brackets();
    fullHeight();
    chartSizes();
    selectInit();
    datepicker();
  });
});
window.addEventListener('load', () => {
  $('.loader').addClass('hide');
  setTimeout(() => {
    $('.loader').addClass('done');
  }, 1000);
});

function isTouchDevice() {
  return (
    'ontouchstart' in window ||
    navigator.maxTouchPoints > 0 ||
    navigator.msMaxTouchPoints > 0
  );
}

function disableScroll(el) {
  var _overlay = el;
  var _clientY = null;
  _overlay.addEventListener(
    'touchstart',
    function (event) {
      if (event.targetTouches.length === 1) {
        _clientY = event.targetTouches[0].clientY;
      }
    },
    false
  );
  _overlay.addEventListener(
    'touchmove',
    function (event) {
      if (event.targetTouches.length === 1) {
        disableRubberBand(event);
      }
    },
    false
  );
  function disableRubberBand(event) {
    var clientY = event.targetTouches[0].clientY - _clientY;
    if (_overlay.scrollTop === 0 && clientY > 0) {
      event.preventDefault();
    }
    if (isOverlayTotallyScrolled() && clientY < 0) {
      event.preventDefault();
    }
  }
  function isOverlayTotallyScrolled() {
    return _overlay.scrollHeight - _overlay.scrollTop <= _overlay.clientHeight;
  }
}

function fullHeight() {
  if (window.innerWidth < 768) {
    $('.dropdown-box, .header-drop').css('height', window.innerHeight);
  } else {
    $('.dropdown-box, .header-drop').removeAttr('style');
  }
}

function dropdown() {
  $(document).on('click', '.js-dropdown-btn', function (e) {
    e.preventDefault();
    $('.dropdown').not($(this).closest('.dropdown')).removeClass('active');
    $(this).closest('.dropdown').toggleClass('active');
    dropdownPosition();
    if (!$(this).closest('.header').length) {
      if ($(this).closest('.dropdown').hasClass('active')) {
        $(this).closest('.nav').addClass('droped');
        $('body').addClass('droped');
      } else {
        setTimeout(function () {
          $('body, .nav').removeClass('droped');
        }, 300);
      }
    }
    if (window.innerWidth < 768) {
      $('html').toggleClass('overflow');
      disableScroll($(this).closest('.dropdown').find('.dropdown-box')[0]);
      $('.dropdown-box').removeAttr('style');
      $(this)
        .closest('.dropdown')
        .find('.dropdown-box')
        .css('height', window.innerHeight);
    }
  });
  $(document).on('click', function (e) {
    var container = $('.dropdown');
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      container.removeClass('active');
      setTimeout(function () {
        $('body, .nav').removeClass('droped');
      }, 300);
    }
  });
  $(document).keyup(function (e) {
    if (e.key === 'Escape') {
      $('.dropdown').removeClass('active');
      setTimeout(function () {
        $('html').removeClass('overflow');
        $('body, .nav').removeClass('droped');
      }, 300);
    }
  });
  $(document).on('click', '.js-close', function (e) {
    e.preventDefault();
    $(this).closest('.dropdown').removeClass('active');
    setTimeout(function () {
      $('html').removeClass('overflow');
      $('body, .nav').removeClass('droped');
    }, 300);
  });
}

function dropdownPosition() {
  $('.dropdown').each(function () {
    var box = $(this).find('.dropdown-box');
    var btn = $(this).find('.dropdown-result');
    if (window.innerWidth > 767) {
      box.removeClass('bottom top');
      box.removeAttr('style');

      var top = btn.offset().top - box.innerHeight();
      var left = btn.offset().left + btn.innerWidth() - box.innerWidth();
      var bottom = btn.offset().top + btn.innerHeight() + box.innerHeight();
      var right = btn.offset().left + box.innerWidth();
      var stateX = false,
        stateY = false;
      if (bottom < window.pageYOffset + window.innerHeight) {
        box.addClass('bottom');
        box.css(
          'top',
          btn.offset().top - $(window).scrollTop() + btn.innerHeight()
        );
        stateY = true;
      } else if (top > window.pageYOffset) {
        box.addClass('top');
        box.css(
          'top',
          btn.offset().top - $(window).scrollTop() - box.innerHeight()
        );
        stateY = true;
      }
      if (right < window.pageXOffset + window.innerWidth) {
        box.css('left', btn.offset().left);
        stateX = true;
      } else if (left > window.pageXOffset) {
        box.css('left', left);
        stateX = true;
      }
      if (!stateX) {
        box.addClass('left');
        box.css('left', btn.offset().left);
      }
      if (!stateY) {
        box.addClass('bottom');
        box.css(
          'top',
          btn.offset().top - $(window).scrollTop() + btn.innerHeight()
        );
      }
    }
  });
}

function video() {
  $('.video-btn').on('click', function () {
    var video = $(this).closest('.video');
    video.addClass('active');
    video.find('video source').each(function () {
      $(this).attr('src', $(this).attr('data-src'));
    });
    video.find('video')[0].load();
    video.find('video')[0].play();
  });
}

function field() {
  $('.field').on('mousedown', function (e) {
    e.stopPropagation();
  });
  $('.field').on('focus', function () {
    $(this).addClass('focus');
  });
  $('.field').on('blur', function () {
    $(this).removeClass('focus');
  });
}

function changeTab(e, btn) {
  // e.preventDefault();
  var tab = $('#' + btn.attr('data-tab'));
  var tabs = tab.closest('.tabs');
  btn.closest('.nav').find('[data-tab]').stop().removeClass('active');
  btn.stop().addClass('active');
  tabs.css({ height: tabs.innerHeight(), transition: '0s' });
  tabs
    .children('.tabs-item:visible')
    .stop()
    .fadeOut(300, function () {
      tab.stop().fadeIn(300, function () {
        tabs.removeAttr('style');
      });
      fittext();
      if (tab.find('.brackets').length) {
        brackets();
      }
    })
    .filter(':first')
    .click();
}

function tabsEvents() {
  $(document).on('click', '.js-tab-btn', function (e) {
    if ($(this).find('.dropdown').length) {
      if (
        !$(this).find('.dropdown-box').is(e.target) &&
        $(this).find('.dropdown-box').has(e.target).length === 0 &&
        !$(e.target).hasClass('dropdown-result__icon')
      ) {
        changeTab(e, $(this));
      }
    } else {
      changeTab(e, $(this));
    }
  });
  if (window.location.hash) {
    $('[href="' + window.location.hash + '"]').click();
  }
}

function tabsInit() {
  $('.tabs').each(function (i, item) {
    if ($(item).children('.tabs-item').length > 1) {
      $(item)
        .children('.tabs-item:not(:first)')
        .hide(0, function () {
          $(item).addClass('active');
        });
    } else {
      $(item).addClass('active');
    }
  });
}

function fittext() {
  fitty('.js-fittext', {
    maxSize: 96,
  });
}

function scheduleSlider() {
  var scheduleEl = '.js-schedule-slider';
  var schedule = new Swiper(scheduleEl, {
    slidesPerView: 'auto',
    watchSlidesVisibility: true,
    navigation: {
      prevEl: '.swiper-button-prev',
      nextEl: '.swiper-button-next',
    },
  });
  var val = parseInt(
    $(scheduleEl).innerWidth() /
      $(scheduleEl).find('.swiper-slide').innerWidth()
  );
  var slides = $(scheduleEl).find('.swiper-slide').length;
  if (slides <= val && slides > 1) {
    $('.schedule-slider').addClass('simple');
  } else if (slides == 1) {
    $('.schedule-slider').addClass('single');
  }
}

function selectInitDesktop(item) {
  function scroll() {
    var options = $('.select2-results__options');
    var results = $('.select2-results');
    if (results.innerHeight() < options.innerHeight()) {
      if (
        typeof results.attr('style') !== typeof undefined &&
        results.attr('style') !== false
      ) {
        results.css('width', options.innerWidth());
      } else {
        results.css('width', options.innerWidth() + 5);
      }
      setTimeout(function () {
        new SimpleBar(results.get(0));
      }, 0);
    }
  }
  $(item)
    .select2({
      minimumResultsForSearch: -1,
      width: '100%',
      selectionCssClass: 'style-' + $(item).attr('data-style'),
      dropdownCssClass:
        'style-' + $(item).attr('data-style') + ' ' + $(item).attr('data-drop'),
      dropdownAutoWidth: true,
    })
    .on('select2:open', function (e) {
      if (window.innerWidth > 768) {
        setTimeout(function () {
          $(item).closest('.select').addClass('active');
          $('.select2-dropdown').addClass('active');
          setTimeout(function () {
            scroll();
          }, 0);
        }, 100);
      }
    })
    .on('select2:closing', function (e) {
      if (window.innerWidth > 767) {
        $(item).closest('.select').removeClass('active');
        $('.select2-dropdown').removeClass('active');
      }
    });
}
function selectInitMobile(item) {
  var select = $(item).closest('.select');
  $(item)
    .select2({
      minimumResultsForSearch: -1,
      width: '100%',
      closeOnSelect: false,
      selectionCssClass: 'style-' + $(item).attr('data-style'),
      dropdownCssClass:
        'style-' + $(item).attr('data-style') + ' ' + $(item).attr('data-drop'),
      dropdownAutoWidth: true,
      dropdownParent: select.find('.select-drop'),
    })
    .on('select2:closing', function (e) {
      if (window.innerWidth < 768) {
        e.preventDefault();
      }
    })
    .on('select2:closed', function (e) {
      if (window.innerWidth < 768) {
        $(item).select2('open');
      }
    })
    .on('select2:select', function (e) {
      if (window.innerWidth < 768) {
        select.toggleClass('active');
        if (!select.hasClass('active')) {
          setTimeout(function () {
            $('html').removeClass('overflow');
            $('body').removeClass('selected');
            for (var i = 0; i < window.selectedElements.length; i++) {
              $(item).closest(window.selectedElements[i]).removeClass('active');
            }
          }, 300);
        }
      }
    })
    .on('select2:open', function (e) {
      const evt = 'scroll.select2';
      $(e.target).parents().off(evt);
      $(window).off(evt);
    });
  $(item).select2('open');
  select.find('.select-btn').on('click', function (e) {
    if (window.innerWidth < 768) {
      if (!select.hasClass('active')) {
        $('.select').removeClass('active');
      }
      select.find('.select-drop').css({
        width: window.innerWidth,
        height: window.innerHeight,
      });
      select.addClass('active');
      $('html').addClass('overflow');
      $('body').addClass('selected');
      for (var i = 0; i < window.selectedElements.length; i++) {
        $(item).closest(window.selectedElements[i]).addClass('active');
      }
      if (select.closest('.popup').length) {
        disableScroll(select.find('.select2-results')[0]);
      }
    }
  });
  select.find('.js-close').on('click', function () {
    if (window.innerWidth < 768) {
      select.removeClass('active');
      setTimeout(function () {
        $('html').removeClass('overflow');
        $('body').removeClass('selected');
        for (var i = 0; i < window.selectedElements.length; i++) {
          $(item).closest(window.selectedElements[i]).removeClass('active');
        }
      }, 300);
    }
  });
}
function selectEvents() {
  $(document).on('click', function (e) {
    var container = $('.select');
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      container.removeClass('active');
      $('.select2-dropdown').removeClass('active');
    }
  });
}
function selectDestroy() {
  $('.js-select').select2('destroy');
  window.select2Desktop = false;
  window.select2Mobile = false;
}
function selectInit() {
  if (window.innerWidth < 768) {
    if (!window.select2Mobile) {
      if (window.select2Desktop) {
        $('.js-select').each(function (i, item) {
          $(item).select2('destroy');
        });
        window.select2Desktop = false;
      }
      window.select2Mobile = true;
      $('.js-select').each(function (i, item) {
        selectInitMobile(item);
      });
    }
  } else {
    if (!window.select2Desktop) {
      if (window.select2Mobile) {
        $('.js-select').each(function (i, item) {
          $(item).select2('destroy');
        });
        window.select2Mobile = false;
      }
      window.select2Desktop = true;
      $('.js-select').each(function (i, item) {
        selectInitDesktop(item);
      });
    }
  }
}

function datepickerInitDesktop() {
  $('.js-datepicker').each(function (i, item) {

    let date = $(item).find('input').val();
    let initialized = false;

    $(item)
      .find('input')
      .datepicker({
        language: 'en',
        autoClose: true,
        onShow: function () {
          $(item).addClass('active');
        },
        onHide: function () {
          $(item).removeClass('active');
        },
        onSelect: function (formattedDate, date, inst) {
          if (!date) {
            $(item).find('input').datepicker().data('datepicker').hide();
          }

          if (formattedDate.length > 0) {
            $(item).addClass('selected');
            $(item).find('.datepicker-el__btn-text').text(formattedDate);
          } else {
            $(item).removeClass('selected');
            $(item).find('.datepicker-el__btn-text').text('choose');
          }

          if (initialized) {
            setTimeout(() => $(item).trigger('datepickerupdated'), 200);
          }
        }
      });
      if (date) {
        $(item).find('input').datepicker().data('datepicker').selectDate(new Date(date));
      }
      initialized = true;
  });
}

function datepickerInitMobile() {
  $('.js-datepicker').each(function (i, item) {
    let date = $(item).find('input').val();
    let initialized = false;

    $(item)
      .find('input')
      .datepicker({
        language: 'en',
        inline: true,
        onSelect: function (formattedDate, date, inst) {
          if (formattedDate.length > 0) {
            $(item).addClass('selected');
            $(item).find('.datepicker-el__btn-text').text(formattedDate);
            $(item).removeClass('active');
            setTimeout(function () {
              $('html').removeClass('overflow');
              $('body').removeClass('selected');
              for (var i = 0; i < window.selectedElements.length; i++) {
                $(item)
                  .closest(window.selectedElements[i])
                  .removeClass('active');
              }
            }, 300);
          } else {
            $(item).removeClass('selected');
            $(item).find('.datepicker-el__btn-text').text('choose');
          }
          if (initialized) {
            setTimeout(() => $(item).trigger('datepickerupdated'), 200);
          }
        },
      });

    if (date) {
      $(item).find('input').datepicker().data('datepicker').selectDate(new Date(date));
    }
    initialized = true;

    $(item)
      .find('.datepicker-el__btn')
      .on('click', function () {
        $(item).find('.datepicker-el__drop').css({
          width: window.innerWidth,
          height: window.innerHeight,
        });
        $(item).addClass('active');
        $('html').addClass('overflow');
        $('body').addClass('selected');
        for (var i = 0; i < window.selectedElements.length; i++) {
          $(item).closest(window.selectedElements[i]).addClass('active');
        }
        if ($(item).closest('.popup').length) {
          disableScroll($(item).find('.datepicker-el__inner')[0]);
        }
      });
    $(item)
      .find('.js-close')
      .on('click', function () {
        $(item).removeClass('active');
        setTimeout(function () {
          $('html').removeClass('overflow');
          $('body').removeClass('selected');
          for (var i = 0; i < window.selectedElements.length; i++) {
            $(item).closest(window.selectedElements[i]).removeClass('active');
          }
        }, 300);
      });
  });
}

function datepicker() {
  if (window.innerWidth < 768) {
    if (!window.datepickerMobile) {
      if (window.datepickerDesktop) {
        $('.js-datepicker').each(function (i, item) {
          $(item).find('input').datepicker().data('datepicker').destroy();
        });
        window.datepickerDesktop = false;
      }
      window.datepickerMobile = true;
      datepickerInitMobile();
    }
  } else {
    if (!window.datepickerDesktop) {
      if (window.datepickerMobile) {
        $('.js-datepicker').each(function (i, item) {
          $(item).find('input').datepicker().data('datepicker').destroy();
        });
        window.datepickerMobile = false;
      }
      window.datepickerDesktop = true;
      datepickerInitDesktop();
    }
  }
}

function popupOpen(popup) {
  if (popup.length) {
    $('.popups').addClass('active anime');
    popup.addClass('active anime');
    popup.find('.popup-wrap').addClass('active');
    $('html, body').addClass('popuped');
    // disableScroll(popup.find('.popup-wrap')[0]);
  }
}

function popupClose(popup) {
  if (popup.length) {
    popup.removeClass('anime');
    $('.popups').removeClass('anime');
    setTimeout(function () {
      popup.removeClass('active');
      popup.find('.popup-wrap').removeClass('active');
      $('.popups').removeClass('active');
      $('html, body').removeClass('popuped');
    }, 300);
  }
}

function popupChange(popupHide, popupShow) {
  if (popupHide.length && popupShow.length) {
    popupHide.removeClass('anime');
    setTimeout(function () {
      popupHide.removeClass('active');
      popupShow.addClass('active anime');
    }, 300);
  }
}

function popup() {
  $(document).on('click', '.js-popup-open', function (e) {
    e.preventDefault();
    popupOpen($('#' + $(this).attr('data-popup')));
  });
  $(document).on('click', '.js-popup-close', function (e) {
    e.preventDefault();
    popupClose($(this).closest('.popup'));
  });
  $('.js-popup-change').on('click', function (e) {
    e.preventDefault();
    popupChange(
      $('#' + $(this).attr('data-popup-hide')),
      $('#' + $(this).attr('data-popup-show'))
    );
  });
  $(document).keyup(function (e) {
    if (e.key === 'Escape') {
      popupClose($('.popup.active'));
    }
  });
  var hashUrl = window.location.hash.substr(1);
  if (hashUrl.length > 0) {
    if ($('#' + hashUrl).length) {
      if ($('#' + hashUrl).hasClass('popup')) {
        popupOpen($('#' + hashUrl));
      }
    }
  }
}

function checkbox() {
  $('.checkbox--toggler')
    .find('input[type=checkbox]')
    .on('change', function () {
      var text = $(this).closest('.checkbox').find('.checkbox-text');
      if ($(this).prop('checked') == true) {
        if (
          typeof text.attr('data-text-in') !== typeof undefined &&
          text.attr('data-text-in') !== false
        ) {
          text.text(text.attr('data-text-in'));
        }
        $(this).closest('[data-checkbox]').removeClass('disabled');
        $(this)
          .closest('[data-checkbox]')
          .find('input')
          .not($(this))
          .removeClass('disabled');
        $(this)
          .closest('[data-checkbox]')
          .find('.sort')
          .removeClass('disabled');
      } else {
        if (
          typeof text.attr('data-text-out') !== typeof undefined &&
          text.attr('data-text-out') !== false
        ) {
          text.text(text.attr('data-text-out'));
        }
        $(this).closest('[data-checkbox]').addClass('disabled');
        $(this)
          .closest('[data-checkbox]')
          .find('input')
          .not($(this))
          .addClass('disabled');
        $(this).closest('[data-checkbox]').find('.sort').addClass('disabled');
      }
    });
}

function cellEdit() {
  $('.js-cell-edit').on('click', function () {
    $('.js-cell-edit').removeClass('active');
    $(this).addClass('active');
  });
  $(document).on('click', function (e) {
    var container = $('.js-cell-edit.active');
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      // container.find('input').val(container.find('.cell-edit__text').text());
      container.removeClass('active');
    }
  });
  $(document).keyup(function (e) {
    if (e.key === 'Escape') {
      if ($('.js-cell-edit.active').length) {
        $('.js-cell-edit.active')
          .find('input')
          .val($('.js-cell-edit.active').find('.cell-edit__text').text());
        $('.js-cell-edit.active').removeClass('active');
      }
    }
    if (e.key === 'Enter') {
      if ($('.js-cell-edit.active').length) {
        if (
          $('.js-cell-edit.active').find('.cell-edit__input').val().length > 0
        ) {
          $('.js-cell-edit.active')
            .find('.cell-edit__text')
            .text($('.js-cell-edit.active').find('.cell-edit__input').val());
          $('.js-cell-edit.active').removeClass('active');
        }
      }
    }
  });
}

function groupScoreEdit() {

    $('.js-group-score').on('keydown', function(e) {
      if (e.key.toLowerCase() === 'tab') {
        return;
      } else if (!e.key.match(/^\d$/)) {

        if (e.key.toLowerCase() === 'enter') {
          const $container = $(e.currentTarget).closest('.groups');
          const $group = $(e.currentTarget).closest('.group');
          const $groups = $container.find('.group');
          const index = $groups.index($group);
          if ($groups[index + 1]) {
            $($groups[index + 1]).find('.js-group-score:first').focus();
          }
        }

        e.preventDefault();
        return false;
      }

        let input1 = $(this);
        let input2 = $(this)
            .closest('.js-group-item')
            .find('.js-group-score')
            .not(this);
        input1.val('');
        input2.val('');
        input1.closest('.brackets-item').removeClass('brackets-item--winner');
        input2.closest('.brackets-item').removeClass('brackets-item--winner');
    });
    $('.js-group-score').on('input', function(e) {
        let input1 = $(this);
        let input2 = $(this)
            .closest('.js-group-item')
            .find('.js-group-score')
            .not(this);

        input1.closest('.js-group-result').removeClass('active');
        input2.closest('.js-group-result').removeClass('active');

        let sum = parseInt(input1.attr('data-best-of'));

        let val1 = parseInt(input1.val());
        if (val1 > sum) {
          val1 = sum;
          input1.val(val1);
        }
        if (val1 < 0) {
          val1 = 0;
          input1.val(val1);
        }

        let val2 = sum - val1;
        input2.val(val2);
    });

    $('.js-group-score').on('keyup', function(e) {

    });
    $('.js-group-score').on('blur', function(e) {
        let input1 = $(this);
        let input2 = $(this)
            .closest('.js-group-item')
            .find('.js-group-score')
            .not(this);

        let val1 = parseInt(input1.val());
        let val2 = parseInt(input2.val());

        if (!isNaN(val1) && !isNaN(val2) && val1 > val2) {
            input1.closest('.js-group-result').addClass('active');
        }
        if (!isNaN(val1) && !isNaN(val2) && val1 < val2) {
            input2.closest('.js-group-result').addClass('active');
        }
    });
}

function controlFile() {
  $('.js-control-file')
    .find('input')
    .on('change', function (e) {
      var file = e.target.files[0];
      var btn = $(this).closest('.js-control-file').find('.btn');
      var bg = $(this).closest('.control').find('.control-file__bg');
      if (file) {
        var fileType = file['type'];
        var validImageTypes = [
          'image/gif',
          'image/svg',
          'image/jpeg',
          'image/jpg',
          'image/png',
        ];
        if ($.inArray(fileType, validImageTypes) >= 0) {
          var reader = new FileReader();
          reader.onload = function () {
            bg.empty();
            bg.append('<img src="' + reader.result + '" alt="">');
          };
          reader.readAsDataURL(file);
          bg.addClass('active');
          btn.text(btn.attr('data-text-out'));
        }
      } else {
        bg.removeClass('active');
        setTimeout(function () {
          bg.empty();
          btn.text(btn.attr('data-text-in'));
        }, 300);
      }
    });
}

function profileImg() {
  $('.js-profile-picture')
    .find('input')
    .on('change', function (e) {
      var file = e.target.files[0];
      var bg = $(this)
        .closest('.js-profile-picture')
        .find('.a-profile-picture__media');
      if (file) {
        var fileType = file['type'];
        var validImageTypes = [
          'image/jpeg',
          'image/jpg',
          'image/svg',
          'image/png',
        ];
        if ($.inArray(fileType, validImageTypes) >= 0) {
          var reader = new FileReader();
          reader.onload = function () {
            bg.empty();
            if (
              fileType == validImageTypes[0] ||
              fileType == validImageTypes[1]
            ) {
              bg.append(
                '<div class="a-profile-picture__bg"><img src="' +
                  reader.result +
                  '" alt=""></div>'
              );
            } else {
              bg.append(
                '<div class="a-profile-picture__img"><img src="' +
                  reader.result +
                  '" alt=""></div>'
              );
            }
          };
          reader.readAsDataURL(file);
        }
      }
    });
}

function password() {
  $(document).on('click', '.password-btn', function () {
    var password = $(this).closest('.password');
    password.toggleClass('active');
    if (password.hasClass('active')) {
      password.find('input').attr('type', 'text');
    } else {
      password.find('input').attr('type', 'password');
    }
  });
}

function sortStates() {
  $('.sort-rows').each(function () {
    var row = $(this).find('.sort-row');
    if (row.length > 1) {
      $(this).addClass('active');
    } else if (row.length == 1) {
      $(this).find('.sort').addClass('disabled');
    }
  });
}

function autocomplete() {
  function scroll(item) {
    if (window.autocompleteSimplebar) {
      window.autocompleteSimplebar.unMount();
    }
    window.autocompleteSimplebar = new SimpleBar(item.get(0));
  }
  $('.js-autocomplete').each(function (i, item) {
    var options = {
      lookup: [
        'apples',
        'apricot',
        'avocado',
        'bananas',
        'blueberries',
        'cherries',
        'grapefruit',
        'grapes',
        'kiwi fruit',
        'lemons',
        'mangoes',
        'melons',
        'nectarines',
        'oranges',
        'passion fruit',
        'peaches',
        'pears',
        'pineapples',
        'plums',
        'rhubarb',
        'rock melon',
        'strawberries',
        'watermelon',
      ],
      maxHeight: 140,
      minChars: 0,
      beforeRender: function (container, suggestions) {
        scroll(container);
        $(item).closest('.autocomplete').addClass('active');
      },
      onHide: function () {
        $(item).closest('.autocomplete').removeClass('active');
      },
    };
    if (!$(item).closest('.append-template').length) {
      $(item).autocomplete(options);
      $(item)
        .closest('.autocomplete')
        .on('click', function () {
          $(item).focus();
          //$(item).attr('value', '');
          $(item).triggerHandler($.Event('keyup', { keyCode: 65, which: 65 }));
          $(item).trigger('change');
        });
    }
  });
}

function bracketsColSize(item) {
  var cols = $(item).find('.brackets-col').length;
  var colValue = 6;
  var pseudo = 120;

  $(item).find('.brackets-col').removeAttr('style');
  $(item).removeClass('small scroll');

  if (window.innerWidth < 1471 && window.innerWidth > 990) {
    colValue = 5;
    pseudo = 30;
  } else if (window.innerWidth < 991 && window.innerWidth > 767) {
    colValue = 4;
    pseudo = 20;
  } else if (window.innerWidth < 768) {
    colValue = false;
    pseudo = 20;
  }
  if (cols < 5) {
    $(item).addClass('small');
  } else {
    if (cols > colValue) {
      $(item).addClass('scroll');
    }
    if (!colValue) {
      $(item).find('.brackets-col').removeAttr('style');
    } else {
      $(item)
        .find('.brackets-col')
        .css({
          width:
            ($(item).find('.brackets-area').innerWidth() - pseudo) / colValue,
          flex:
            '0 0 ' +
            ($(item).find('.brackets-area').innerWidth() - pseudo) / colValue +
            'px',
        });
    }
  }
}

function fullscreen() {
  $('.js-fullscreen').on('click', function (e) {
    e.preventDefault();
    var $this = $(this);
    var panel = $('#' + $(this).attr('data-fullscreen-panel'));
    var content = $('#' + $(this).attr('data-fullscreen-content'));
    $('body').addClass('anime');
    setTimeout(function () {
      $this.toggleClass('active');
      content.closest('.section').toggleClass('active');
      panel.toggleClass('fixed');
      content.toggleClass('fixed');
      $('.header').toggleClass('hidden');
      $('.a-panel').toggleClass('hidden');
      $('html').toggleClass('overflow');
      if ($this.hasClass('active')) {
        content.css('padding', panel.innerHeight() + 20 + 'px 0 20px 0');
        content.children('div').addClass('dragscroll');
        dragscroll.reset();
      } else {
        content.removeAttr('style');
        content.children('div').removeClass('dragscroll');
        dragscroll.reset();
      }
      if (content.find('.brackets').length) {
        brackets();
      } else if (content.hasClass('schedule-content')) {
        // chart();
      }
      setTimeout(function () {
        $('body').removeClass('anime');
      }, 300);
    }, 300);
  });
}

function brackets() {
  $('.brackets').each(function (i, item) {
    bracketsColSize(item);
    if (!isTouchDevice()) {
      new SimpleBar($(item).find('.brackets-inner').get(0), {
        classNames: { contentWrapper: 'dragscroll' },
      });
      dragscroll.reset();
    } else {
      $(item).addClass('mobileScroll');
    }
  });
}

function headerFixed() {
  if ($(window).scrollTop() > 0) {
    $('.header').addClass('fixed');
  } else {
    $('.header').removeClass('fixed');
  }
}

function headerMenu() {
  $('.js-header-btn').on('click', function () {
    $('.header-drop').css('height', window.innerHeight);
    $('.header-drop').addClass('active anime');
    disableScroll($('.header-drop__inner')[0]);
  });
  $('.js-close').on('click', function (e) {
    e.preventDefault();
    if ($(this).closest('.header-drop').length) {
      $(this).closest('.header-drop').removeClass('anime');
      setTimeout(function () {
        $(this).closest('.header-drop').removeClass('anime');
      }, 300);
    }
  });
}

function scroll() {
  $('.js-scroll').each(function () {
    new SimpleBar($(this).get(0));
  });
  $('.table').each(function () {
    if (!$(this).hasClass('table--static')) {
      new SimpleBar($(this).get(0), {
        classNames: {contentWrapper: 'dragscroll'}
      });
      dragscroll.reset();
    }
  });
}

function chart() {
  if ($('.chart').length) {
    chartSizes();
    // Scroll
    if (!isTouchDevice()) {
      new SimpleBar($('.chart-head__inner').get(0), {
        classNames: { contentWrapper: 'dragscroll' },
      });
      new SimpleBar($('.chart-content').get(0), {
        classNames: { contentWrapper: 'dragscroll' },
      });
      $('.chart-head')
        .find('.dragscroll')
        .on('scroll', function () {
          $('.chart-content')
            .find('.dragscroll')
            .scrollLeft($(this).scrollLeft());
        });
      $('.chart-content')
        .find('.dragscroll')
        .on('scroll', function () {
          $('.chart-head').find('.dragscroll').scrollLeft($(this).scrollLeft());
        });
      dragscroll.reset();
    } else {
      $('.chart-head').on('scroll', function () {
        $('.chart-content').scrollLeft($(this).scrollLeft());
      });
      $('.chart-content').on('scroll', function () {
        $('.chart-head').scrollLeft($(this).scrollLeft());
      });
      $('.chart').addClass('scroll');
    }

    // Active indicator
    var activeIndicator = 10;
    $('.chart-indicator').eq(activeIndicator).addClass('active');

    // Event toggle
    $('.schedule-nav__item')
      .find('.checkbox-input')
      .on('change', function () {
        $('.chart-event[data-type=' + $(this).attr('data-type') + ']').each(
          function () {
            $(this).closest('.chart-row').toggleClass('hidden');
            $('.chart-sidebar__item')
              .eq($(this).closest('.chart-row').index())
              .toggleClass('hidden');
          }
        );
      });

    // Tooltips
    $('.chart-event').each(function () {
      var content = $(this).attr('data-content');
      $(this).tooltipster({
        animation: 'fade',
        contentAsHTML: true,
        content: content,
      });
    });
  }
}

function chartSizes() {
  if ($('.chart').length) {
    // Scroll width
    var chartWidth = 0;
    $('.chart-month').each(function () {
      chartWidth += $(this).innerWidth();
    });
    $('.chart-content__inner').removeAttr('style');
    $('.chart-content__inner').css('width', chartWidth);
    // Rows height
    $('.chart-row').removeAttr('style');
    $('.chart-sidebar__item').each(function (i, item) {
      $('.chart-row').eq(i).css('height', $(this).innerHeight());
    });
    // Chart events
    var chartEventKoef = $('.chart-day').innerWidth();
    $('.chart-event').removeAttr('style');
    $('.chart-event').each(function () {
      $(this).css({
        width: $(this).attr('data-duration') * chartEventKoef,
        left: $(this).attr('data-start') * chartEventKoef,
      });
    });
  }
}

function tableSort() {
  $('.table-sort').on('click', function () {
    $('.table-sort').not($(this)).removeClass('up bottom');
    if (!$(this).hasClass('up') && !$(this).hasClass('bottom')) {
      $(this).addClass('up');
    } else if ($(this).hasClass('up')) {
      $(this).removeClass('up').addClass('bottom');
    } else if ($(this).hasClass('bottom')) {
      $(this).removeClass('bottom');
    }
  });
}

// IMITATION FUNCTIONS
function groupDublicate() {
  $(document).on('click', '.js-group-dublicate', function (e) {
    e.preventDefault();
    $(this).closest('.dropdown').removeClass('active');
    var btn = $(this).closest('.nav-item');
    var tab = $('#' + $(this).closest('.js-tab-btn').attr('data-tab'));
    var btnLength = $(this).closest('.nav-list').find('.nav-item').length;
    selectDestroy();
    var newBtn = btn.clone();
    var newTab = tab.clone();
    newBtn.find('.js-tab-btn').attr('data-tab', 'tab_' + btnLength);
    newBtn.find('.js-tab-btn').removeClass('active');
    newBtn.find('.dropdown').removeClass('active');
    newBtn.css('display', 'none');
    $(this).closest('.nav-list').append(newBtn);
    newBtn.fadeIn(600);

    newTab.attr('id', 'tab_' + btnLength);
    newTab.hide(0);
    newTab.find('.js-tab-btn').each(function () {
      $(this).attr('data-tab', $(this).attr('data-tab') + '_' + btnLength);
    });
    newTab.find('.tabs-item').each(function () {
      $(this).attr('id', $(this).attr('id') + '_' + btnLength);
    });
    tab.parent('.tabs').append(newTab);

    scroll();
    tabsInit();
    selectInit();
  });
}

function showItems() {
  $('.js-show-btn').on('click', function (e) {
    e.preventDefault();
    // Random item for animation example
    var item = $('.js-show-items').children().first().clone();
    $('.js-show-items').append(
      item.css({
        display: 'none',
        transition: '0s',
      })
    );
    item.slideDown(600, function () {
      item.removeAttr('style');
    });
  });
}

function append() {
  $(document).on('click', '.js-add-btn', function (e) {
    e.preventDefault();

    var template = $(this)
      .closest('.append')
      .find('.append-template[data-append=' + $(this).attr('data-append') + ']')
      .find('.append-item[data-append=' + $(this).attr('data-append') + ']');
    var appendItems = $(this)
      .closest('.append')
      .find('.append-wrap[data-append=' + $(this).attr('data-append') + ']');
    selectDestroy();
    var appendItem = template.clone();
    if (template.is('tr')) {
      appendItem.children('td').wrapInner('<div style="display: none;" />');
      appendItems.append(appendItem);
      selectInit();
      autocomplete();
      scroll();
      appendItems
        .find('tr:last')
        .find('td > div')
        .fadeIn(400, function () {
          $(this).replaceWith($(this).contents());
        });
    } else {
      appendItems.append(appendItem);
      selectInit();
      autocomplete();
      scroll();
      appendItems.fadeIn(400);
    }
  });
  $(document).on('click', '.js-add-clear', function (e) {
    e.preventDefault();
    var appendItem = $(this).closest(
      '.append-item[data-append=' + $(this).attr('data-append') + ']'
    );
    if (appendItem.is('tr')) {
      appendItem
        .children('td')
        .wrapInner('<div />')
        .children()
        .fadeOut(function () {
          appendItem.remove();
        });
      return false;
    } else {
      appendItem.fadeOut(function () {
        appendItem.remove();
      });
      return false;
    }
  });
}
