(function ($) {
    "use strict";
    
    var isTouch  = (('ontouchstart' in window) || (navigator.msMaxTouchPoints > 0) || (navigator.maxTouchPoints)),
        isMobile = navigator.userAgent.match(
            /(iPhone|iPod|iPad|Android|playbook|silk|BlackBerry|BB10|Windows Phone|Tizen|Bada|webOS|IEMobile|Opera Mini)/
        ),
        get_url  = function (endpoint) {
            return kuteshop_params.kuteshop_ajax_url.toString().replace(
                '%%endpoint%%',
                endpoint
            );
        };
    
    function setCookie() {
        var d = new Date();
        d.setTime(d.getTime() + (arguments[ 2 ] * 24 * 60 * 60 * 1000));
        var expires     = "expires=" + d.toUTCString();
        document.cookie = arguments[ 0 ] + "=" + arguments[ 1 ] + "; " + arguments[ 2 ];
    }
    
    function getCookie() {
        var name = arguments[ 0 ] + "=",
            ca   = document.cookie.split(';'),
            i    = 0,
            c    = 0;
        for ( ; i < ca.length; ++i ) {
            c = ca[ i ];
            while ( c.charAt(0) == ' ' ) {
                c = c.substring(1);
            }
            if ( c.indexOf(name) == 0 ) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    
    function kuteshop_get_scrollbar_width() {
        var $inner = jQuery('<div style="width: 100%; height:200px;">test</div>'),
            $outer = jQuery('<div style="width:200px;height:150px; position: absolute; top: 0; left: 0; visibility: hidden; overflow:hidden;"></div>').append($inner),
            inner  = $inner[ 0 ],
            outer  = $outer[ 0 ];
        jQuery('body').append(outer);
        var width1 = inner.offsetWidth;
        $outer.css('overflow', 'scroll');
        var width2 = outer.clientWidth;
        $outer.remove();
        return (width1 - width2);
    }
    
    /* Animate */
    $.fn.kuteshop_animation_tabs         = function ($tab_animated) {
        $tab_animated = ($tab_animated === undefined || $tab_animated === '') ? '' : $tab_animated;
        if ( $tab_animated !== '' ) {
            $(this).find('.owl-slick .slick-active, .product-list-grid .product-item').each(function (i) {
                var $this  = $(this),
                    $style = $this.attr('style'),
                    $delay = i * 200;
                
                $style = ($style === undefined) ? '' : $style;
                $this.attr('style', $style +
                    ';-webkit-animation-delay:' + $delay + 'ms;' +
                    '-moz-animation-delay:' + $delay + 'ms;' +
                    '-o-animation-delay:' + $delay + 'ms;' +
                    'animation-delay:' + $delay + 'ms;'
                ).addClass($tab_animated + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                    $this.removeClass($tab_animated + ' animated');
                    $this.attr('style', $style);
                });
            });
        }
    };
    $.fn.kuteshop_better_equal_elems     = function () {
        var $this = $(this);
        $this.on('kuteshop_better_equal_elems', function () {
            setTimeout(function (args) {
                $this.each(function () {
                    if ( $(this).find('.equal-elem').length ) {
                        $(this).find('.equal-elem').css({
                            'height': 'auto'
                        });
                        var $height = 0;
                        $(this).find('.equal-elem').each(function () {
                            if ( $height < $(this).height() ) {
                                $height = $(this).height();
                            }
                        });
                        $(this).find('.equal-elem').height($height);
                    }
                });
            }, 100);
        }).trigger('kuteshop_better_equal_elems');
        $(window).on('resize', function () {
            $this.trigger('kuteshop_better_equal_elems');
        });
    };
    $.fn.kuteshop_category_product       = function () {
        $(this).each(function () {
            var $main = $(this);
            $main.find('.cat-parent').each(function () {
                if ( $(this).hasClass('current-cat-parent') ) {
                    $(this).addClass('show-sub');
                    $(this).children('.children').slideDown(400);
                }
                $(this).children('.children').before('<span class="carets"></span>');
            });
            $main.children('.cat-parent').each(function () {
                var curent = $(this).find('.children');
                $(this).children('.carets').on('click', function () {
                    $(this).parent().toggleClass('show-sub');
                    $(this).parent().children('.children').slideToggle(400);
                    $main.find('.children').not(curent).slideUp(400);
                    $main.find('.cat-parent').not($(this).parent()).removeClass('show-sub');
                });
                var next_curent = $(this).find('.children');
                next_curent.children('.cat-parent').each(function () {
                    var child_curent = $(this).find('.children');
                    $(this).children('.carets').on('click', function () {
                        $(this).parent().toggleClass('show-sub');
                        $(this).parent().parent().find('.cat-parent').not($(this).parent()).removeClass('show-sub');
                        $(this).parent().parent().find('.children').not(child_curent).slideUp(400);
                        $(this).parent().children('.children').slideToggle(400);
                    })
                });
            });
        });
    };
    $.fn.kuteshop_init_lazy_load         = function () {
        $(this).each(function () {
            var $config = [];
            
            $config.beforeLoad     = function (element) {
                if ( element.is('div') == true ) {
                    element.addClass('loading-lazy');
                } else {
                    element.parent().addClass('loading-lazy');
                }
            };
            $config.afterLoad      = function (element) {
                if ( element.is('div') == true ) {
                    element.removeClass('loading-lazy');
                } else {
                    element.parent().removeClass('loading-lazy');
                }
            };
            $config.effect         = "fadeIn";
            $config.enableThrottle = true;
            $config.throttle       = 250;
            $config.effectTime     = 600;
            $config.threshold      = 400;
            if ( $(this).closest('.megamenu, .flex-viewport').length > 0 )
                $config.delay = 0;
            $(this).lazy($config);
        });
    };
    $.fn.kuteshop_countdown              = function () {
        function kuteshop_get_digit() {
            var $text = '',
                $num  = 0,
                i     = 0,
                j     = 1;
            
            for ( ; i < arguments[ 0 ]; ++i ) {
                $num = ~~(arguments[ 1 ] / j) % 10;
                j    = j * 10;
                $text += '<span class="digit">' + $num + '</span>';
            }
            return $text;
        }
        
        var $day  = 0,
            $hour = 0,
            $min  = 0,
            $sec  = 0;
        
        $(this).each(function () {
            var $this           = $(this),
                $text_countdown = '',
                $text_hours     = '',
                $text_mins      = '',
                $text_secs      = '';
            
            $this.countdown($this.data('datetime'), {defer: false})
                .on('update.countdown', function (event) {
                    if ( $this.parent().hasClass('style6') ) {
                        if ( event.strftime('%D') != $day ) {
                            $day = event.strftime('%D');
                            $this.find('.days .curr').html('<span>' + $day + '</span>');
                            $this.find('.days .next').html('<span>' + $day + '</span>');
                            $this.find('.days').addClass('flip');
                        }
                        if ( event.strftime('%H') != $hour ) {
                            $hour = event.strftime('%H');
                            $this.find('.hours .curr').html('<span>' + $hour + '</span>');
                            $this.find('.hours .next').html('<span>' + $hour + '</span>');
                            $this.find('.hours').addClass('flip');
                        }
                        if ( event.strftime('%M') != $min ) {
                            $min = event.strftime('%M');
                            $this.find('.minutes .curr').html('<span>' + $min + '</span>');
                            $this.find('.minutes .next').html('<span>' + $min + '</span>');
                            $this.find('.minutes').addClass('flip');
                        }
                        if ( event.strftime('%S') != $sec ) {
                            $sec = event.strftime('%S');
                            $this.find('.seconds .curr').html('<span>' + $sec + '</span>');
                            $this.find('.seconds .next').html('<span>' + $sec + '</span>');
                            $this.find('.seconds').addClass('flip');
                        }
                        setTimeout(function () {
                            $this.find('.time').removeClass('flip');
                        }, 500);
                    } else {
                        if ( $this.parent().hasClass('default') ) {
                            var $length_hours = event.strftime('%I').toString().length,
                                $num_hours    = event.strftime('%I'),
                                $length_mins  = event.strftime('%M').toString().length,
                                $num_mins     = event.strftime('%M'),
                                $length_secs  = event.strftime('%S').toString().length,
                                $num_secs     = event.strftime('%S');
                            
                            $text_hours     = '<span class="hours">' + kuteshop_get_digit($length_hours, $num_hours) + '</span>';
                            $text_mins      = '<span class="mins">' + kuteshop_get_digit($length_mins, $num_mins) + '</span>';
                            $text_secs      = '<span class="secs">' + kuteshop_get_digit($length_secs, $num_secs) + '</span>';
                            $text_countdown = $text_hours + $text_mins + $text_secs;
                        } else {
                            $text_countdown = event.strftime(
                                '<span class="days"><span class="number">%D</span><span class="text">' + kuteshop_params.days_text + '</span></span>' +
                                '<span class="hour"><span class="number">%H</span><span class="text">' + kuteshop_params.hrs_text + '</span></span>' +
                                '<span class="mins"><span class="number">%M</span><span class="text">' + kuteshop_params.mins_text + '</span></span>' +
                                '<span class="secs"><span class="number">%S</span><span class="text">' + kuteshop_params.secs_text + '</span></span>'
                            );
                        }
                        $this.html($text_countdown);
                    }
                });
        });
    };
    $.fn.kuteshop_init_popup             = function () {
        var $this = $(this);
        $this.on('kuteshop_init_popup', function () {
            $this.each(function () {
                var $popup = $(this);
                if ( kuteshop_params.enable_popup_mobile != 1 ) {
                    if ( $(window).innerWidth() <= 992 ) {
                        return;
                    }
                }
                var disabled_popup_by_user = getCookie('kuteshop_disabled_popup_by_user');
                if ( disabled_popup_by_user == 'true' ) {
                    return;
                } else {
                    if ( $('body').hasClass('home') && kuteshop_params.enable_popup == 1 ) {
                        setTimeout(function () {
                            $popup.modal({
                                keyboard: false
                            });
                            $popup.find('.lazy').lazy({
                                delay: 0
                            });
                        }, kuteshop_params.popup_delay_time);
                    }
                }
                $(document).on('change', '.kuteshop_disabled_popup_by_user', function () {
                    if ( $(this).is(":checked") ) {
                        setCookie('kuteshop_disabled_popup_by_user', 'true', 7);
                    } else {
                        setCookie('kuteshop_disabled_popup_by_user', '', 0);
                    }
                });
            });
        }).trigger('kuteshop_init_popup');
    };
    $.fn.kuteshop_sticky_header          = function () {
        var $this = $(this);
        $this.on('kuteshop_sticky_header', function () {
            $this.each(function () {
                $(document).on('scroll', function (ev) {
                    if ( $(window).width() > 1024 ) {
                        var $head   = document.getElementById('header'),
                            $head   = document.getElementsByClassName('block-nav-category')[ 0 ],
                            $sticky = document.getElementById('header-sticky-menu'),
                            $height = $($head).height();
                        
                        if ( $(window).scrollTop() > $height + 100 ) {
                            $sticky.classList.add('active');
                        } else {
                            $sticky.classList.remove('active');
                            if ( $head != null ) {
                                $head.classList.remove('has-open');
                            }
                        }
                    }
                });
            });
        }).trigger('kuteshop_sticky_header');
    };
    $.fn.kuteshop_alert_variable_product = function () {
        var $this = $(this);
        $this.on('kuteshop_alert_variable_product', function () {
            if ( $(this).hasClass('disabled') ) {
                $(this).popover({
                    content: kuteshop_params.alert_variable,
                    trigger: 'hover',
                    placement: 'bottom'
                });
            } else {
                $(this).popover('destroy');
            }
        }).trigger('kuteshop_alert_variable_product');
        $(document).on('change', function () {
            $this.trigger('kuteshop_alert_variable_product');
        });
    };
    $.fn.kuteshop_product_thumbnail      = function () {
        $(this).not('.slick-initialized').each(function () {
            var $this   = $(this),
                $config = [];
            
            if ( $('body').hasClass('rtl') ) {
                $config.rtl = true;
            }
            $config.prevArrow     = '<span class="pe-7s-angle-left"></span>';
            $config.nextArrow     = '<span class="pe-7s-angle-right"></span>';
            $config.focusOnSelect = true;
            $config.infinite      = false;
            $config.slidesToShow  = 3;
            $config.slidesMargin  = 0;
            $config.cssEase       = 'linear';
            $this.slick($config);
        });
    };
    $.fn.kuteshop_init_carousel          = function () {
        $(this).not('.slick-initialized').each(function () {
            var $this       = $(this),
                $responsive = $this.data('responsive'),
                $config     = [];
            
            if ( $('body').hasClass('rtl') ) {
                $config.rtl = true;
            }
            $config.slidesMargin = 0;
            if ( $this.hasClass('custom-dots') ) {
                $config.customPaging = function (slider, i) {
                    var thumb = $(slider.$slides[ i ]).data('thumb');
                    return '<figure><img src="' + thumb + '" alt="kuteshop"></figure>';
                };
            }
            if ( $this.hasClass('slick-vertical') ) {
                $config.prevArrow = '<span class="pe-7s-angle-up"></span>';
                $config.nextArrow = '<span class="pe-7s-angle-down"></span>';
            } else {
                $config.prevArrow = '<span class="pe-7s-angle-left"></span>';
                $config.nextArrow = '<span class="pe-7s-angle-right"></span>';
            }
            $config.responsive   = $responsive;
            $config.swipeToSlide = true;
            
            $this.slick($config);
            $this.on('beforeChange', function (event, slick, currentSlide) {
                $this.find('.lazy').kuteshop_init_lazy_load();
            });
            if ( $this.hasClass('slick-vertical') ) {
                if ( $this.hasClass('equal-container').length ) {
                    $this.kuteshop_better_equal_elems();
                }
            }
            if ( $this.hasClass('custom-dots') ) {
                $this.trigger('add_custom_dots_slide');
            }
        });
    };
    $.fn.kuteshop_jump_section           = function () {
        $('.kuteshop-tabs').each(function (index, el) {
            $(this).find('.section-down').on('click', function (e) {
                if ( $('.kuteshop-tabs').eq(index + 1).length == 1 ) {
                    $('html, body').animate({
                        scrollTop: $('.kuteshop-tabs').eq(index + 1).offset().top - 100
                    }, 'slow');
                }
                
                e.preventDefault();
            });
            $(this).find('.section-up').on('click', function (e) {
                if ( $('.kuteshop-tabs').eq(index - 1).length == 1 ) {
                    $('html, body').animate({
                        scrollTop: $('.kuteshop-tabs').eq(index - 1).offset().top - 100
                    }, 'slow');
                }
                
                e.preventDefault();
            });
        });
    };
    
    $(document).on('click', '.kuteshop-tabs .tab-link a', function (e) {
        var $this         = $(this),
            $ID           = $this.data('id'),
            $tabID        = $this.attr('href'),
            $ajax_tabs    = $this.data('ajax'),
            $sectionID    = $this.data('section'),
            $tab_animated = $this.data('animate'),
            $loaded       = $this.closest('.tab-link').find('a.loaded').attr('href');
        
        if ( $ajax_tabs == 1 && !$this.hasClass('loaded') ) {
            $($tabID).closest('.tab-container').addClass('loading');
            $this.parent().addClass('active').siblings().removeClass('active');
            $.ajax({
                type: 'POST',
                url: get_url('content_ajax_tab'),
                data: {
                    security: kuteshop_params.security,
                    id: $ID,
                    section_id: $sectionID,
                },
                success: function (response) {
                    if ( response[ 'success' ] == 'ok' ) {
                        $($tabID).html($(response[ 'html' ]).find('.vc_tta-panel-body').html());
                        $($loaded).html('');
                        $($tabID).closest('.tab-container').removeClass('loading');
                        $('[href="' + $loaded + '"]').removeClass('loaded');
                        $($tabID).addClass('active').siblings().removeClass('active');
                        $this.addClass('loaded');
                    } else {
                        $($tabID).html('<strong>Error: Can not Load Data ...</strong>');
                    }
                },
                complete: function () {
                    if ( $($tabID).find('.kuteshop-countdown').length ) {
                        $($tabID).find('.kuteshop-countdown').kuteshop_countdown();
                    }
                    if ( $($tabID).find('.owl-slick').length ) {
                        $($tabID).find('.owl-slick').kuteshop_init_carousel();
                    }
                    if ( $($tabID).find('.equal-container.better-height').length ) {
                        $($tabID).find('.equal-container.better-height').kuteshop_better_equal_elems();
                    }
                    setTimeout(function () {
                        $($tabID).kuteshop_animation_tabs($tab_animated);
                    }, 100)
                }
            });
        } else {
            $this.parent().addClass('active').siblings().removeClass('active');
            $($tabID).addClass('active').siblings().removeClass('active');
            $($tabID).kuteshop_animation_tabs($tab_animated);
        }
        
        $this.closest('.kuteshop-tabs').find('.cat-filter').removeClass('cat-active');
        
        e.preventDefault();
    });
    
    $(document).on('click', '.filter-tabs .cat-filter', function (e) {
        var $this      = $(this),
            _container = $this.closest('.content-tabs').find('.tab-panel.active'),
            _catID     = $this.data('cat'),
            _tabID     = $this.data('id'),
            _check     = 1;
        if ( $this.hasClass('cat-active') ) {
            _check = 0;
        }
        if ( $this.closest('.filter-tabs').data('filter') == 'yes' ) {
            _container.find('.kuteshop-products').each(function () {
                var _target       = $(this),
                    _containerID  = _target.data('self_id'),
                    _productStyle = _target.data('list_style');
                
                if ( _containerID != '' ) {
                    $('.' + _containerID).addClass('loading');
                    $.ajax({
                        type: 'POST',
                        url: get_url('ajax_tab_filter'),
                        data: {
                            security: kuteshop_params.security,
                            cat: _catID,
                            id: _tabID,
                            check: _check,
                            product_id: _containerID,
                            list_style: _productStyle,
                        },
                        success: function (response) {
                            $this.closest('.filter-tabs').find('.cat-filter').removeClass('cat-active');
                            if ( response[ 'success' ] == 'ok' ) {
                                if ( _productStyle == 'owl' ) {
                                    $('.' + _containerID).children('.content-product-append').slick('unslick');
                                }
                                if ( _check == 1 ) {
                                    $('.' + _containerID).children('.content-product-append').html($(response[ 'html' ]));
                                    $this.addClass('cat-active');
                                } else {
                                    $('.' + _containerID).children('.content-product-append').html($(response[ 'html' ]).children().html());
                                }
                            } else {
                                $('.' + _containerID).html('<strong>Error: Can not Load Data ...</strong>');
                            }
                            $('.' + _containerID).removeClass('loading');
                        },
                        complete: function () {
                            if ( $('.' + _containerID).find('.kuteshop-countdown').length ) {
                                $('.' + _containerID).find('.kuteshop-countdown').kuteshop_countdown();
                            }
                            if ( $('.' + _containerID).find('.owl-slick').length ) {
                                $('.' + _containerID).find('.owl-slick').kuteshop_init_carousel();
                            }
                            if ( $('.' + _containerID).find('.equal-container.better-height').length ) {
                                $('.' + _containerID).find('.equal-container.better-height').kuteshop_better_equal_elems();
                            }
                        }
                    });
                    
                    e.preventDefault();
                }
            });
        }
    });
    
    $(document).on('click', function (event) {
        var $target = $(event.target).closest('.kuteshop-dropdown'),
            $parent = $('.kuteshop-dropdown');
        
        if ( $target.length > 0 ) {
            $parent.not($target).removeClass('open');
            if (
                $(event.target).is('[data-kuteshop="kuteshop-dropdown"]') ||
                $(event.target).closest('[data-kuteshop="kuteshop-dropdown"]').length > 0
            ) {
                $target.toggleClass('open');
                event.preventDefault();
            }
        } else {
            $('.kuteshop-dropdown').removeClass('open');
        }
    });
    
    $('.body-overlay').on('click', function (e) {
        $('html').css('overflow', 'visible');
        $('body').removeClass('box-mobile-menu-open');
        $('.ovic-menu-clone-wrap').removeClass('open');
        e.preventDefault();
    });
    
    /* QUANTITY */
    if ( !String.prototype.getDecimals ) {
        String.prototype.getDecimals = function () {
            var num   = this,
                match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
            if ( !match ) {
                return 0;
            }
            return Math.max(0, (match[ 1 ] ? match[ 1 ].length : 0) - (match[ 2 ] ? +match[ 2 ] : 0));
        };
    }
    $(document).on('click', '.quantity-plus, .quantity-minus', function (e) {
        e.preventDefault();
        // Get values
        var $qty       = $(this).closest('.quantity').find('.qty'),
            currentVal = parseFloat($qty.val()),
            max        = parseFloat($qty.attr('max')),
            min        = parseFloat($qty.attr('min')),
            step       = $qty.attr('step');
        
        if ( !$qty.is(':disabled') ) {
            // Format values
            if ( !currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
            if ( max === '' || max === 'NaN' ) max = '';
            if ( min === '' || min === 'NaN' ) min = 0;
            if ( step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN' ) step = '1';
            
            // Change the value
            if ( $(this).is('.quantity-plus') ) {
                if ( max && (currentVal >= max) ) {
                    $qty.val(max);
                } else {
                    $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
                }
            } else {
                if ( min && (currentVal <= min) ) {
                    $qty.val(min);
                } else if ( currentVal > 0 ) {
                    $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
                }
            }
            
            // Trigger change event
            $qty.trigger('change');
        }
    });
    
    $(document).on('click', 'a.backtotop', function (e) {
        $('html, body').animate({scrollTop: 0}, 800);
        e.preventDefault();
    });
    
    $(document).on('click', '.toggle-category', function (e) {
        $(this).closest('.kuteshop-tabs').toggleClass('cat-active');
        e.preventDefault();
    });
    
    $(document).on('click', '.product-summary-action', function (e) {
        if ( $(this).hasClass('close') ) {
            $(this).closest('.product').removeClass('active');
        } else {
            $(this).closest('.product').addClass('active');
        }
        e.preventDefault();
    });
    
    /* ADD TO CART SINGLE PRODUCT */
    var serializeObject = function (form) {
        var o = {};
        var a = form.serializeArray();
        $.each(a, function () {
            if ( o[ this.name ] ) {
                if ( !o[ this.name ].push ) {
                    o[ this.name ] = [ o[ this.name ] ];
                }
                o[ this.name ].push(this.value || '');
            } else {
                o[ this.name ] = this.value || '';
            }
        });
        return o;
    };
    $(document).on('click', '.product:not(.product-type-external) .single_add_to_cart_button', function (e) {
        
        var $thisbutton = $(this);
        
        if ( !$thisbutton.hasClass('disabled') && kuteshop_params.enable_ajax_product ) {
            
            var form = $thisbutton.closest('form'),
                data = serializeObject(form);
            
            if ( $thisbutton.val() ) {
                data.product_id = $thisbutton.val();
            }
            
            $thisbutton.addClass('loading');
            
            // Trigger event.
            $(document.body).trigger('adding_to_cart', [ $thisbutton, data ]);
            
            // Ajax action.
            $.post(get_url('add_to_cart_single'), data, function (response) {
                
                $thisbutton.removeClass('loading');
                
                if ( !response ) {
                    return;
                }
                
                // Redirect to cart option
                if ( wc_add_to_cart_params.cart_redirect_after_add === 'yes' || $thisbutton.hasClass('buy-now') ) {
                    window.location = wc_add_to_cart_params.cart_url;
                    return;
                }
                
                // Trigger event so themes can refresh other areas.
                $(document.body).trigger('added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ]);
                
            });
            e.preventDefault();
            
        }
    });
    
    $(document).on('change', '.per-page-form .option-perpage', function () {
        $(this).closest('form').submit();
    });
    
    $(window).on('scroll', function () {
        if ( $(window).scrollTop() > 200 ) {
            $('.backtotop').addClass('active');
        } else {
            $('.backtotop').removeClass('active');
        }
    });
    
    $(document).on('vc-full-width-row', function (event) {
        if ( $(event.target).find('[data-vc-full-width="true"]').length && $('body').hasClass('rtl') ) {
            var $elements = $(event.target).find('[data-vc-full-width="true"]');
            $.each($elements, function () {
                $(this).css('padding-left', $(this).css('padding-right'));
            });
        }
    });
    
    $(document).on('add_custom_dots_slide', function (event) {
        $(event.target).find('.slick-dots').not('.slick-initialized').each(function () {
            var $config = [];
            if ( $('body').hasClass('rtl') ) {
                $config.rtl = true;
            }
            $config.slidesToShow  = 3;
            $config.initialSlide  = 1;
            $config.centerPadding = 0;
            $config.slidesMargin  = 0;
            $config.infinite      = true;
            $config.centerMode    = true;
            $config.focusOnSelect = true;
            $config.arrows        = false;
            $config.dots          = true;
            $(this).slick($config);
        });
    });
    
    $(document).ajaxComplete(function (event, xhr, settings) {
        if ( xhr.status == 200 && xhr.responseText ) {
            if ( $('.lazy').length > 0 ) {
                $('.lazy').kuteshop_init_lazy_load();
            }
        }
    });
    
    /* START - VERTICAL CATEGORY */
    $.fn.kuteshop_auto_width_vertical_menu = function () {
        var $this = $(this);
        $this.on('kuteshop_auto_width_vertical_menu', function () {
            if ( $(window).innerWidth() > 1024 && $(this).find('.megamenu').length ) {
                $(this).each(function () {
                    var _width1 = parseInt($('.container').innerWidth()) - 30,
                        _width2 = parseInt($(this).outerWidth()),
                        _value  = (_width1 - _width2);
                    
                    $(this).find('.megamenu').each(function () {
                        $(this).css('max-width', _value + 'px');
                    });
                });
            }
        }).trigger('kuteshop_auto_width_vertical_menu');
        $(window).on('resize', function () {
            $this.trigger('kuteshop_auto_width_vertical_menu');
        });
    };
    $.fn.kuteshop_category_vertical        = function () {
        /* SHOW ALL ITEM */
        var $countLi = 0;
        
        $(this).each(function () {
            var $dataItem = $(this).data('items') - 1;
            $countLi      = $(this).find('.vertical-menu>li').length;
            
            if ( $countLi > ($dataItem + 1) ) {
                $(this).addClass('show-button-all');
            }
            $(this).find('.vertical-menu>li').each(function (i) {
                $countLi = $countLi + 1;
                if ( i > $dataItem ) {
                    $(this).addClass('link-other');
                }
            });
        });
    };
    $(document).on('click', '.open-cate', function (e) {
        $(this).closest('.block-nav-category').find('li.link-other').each(function () {
            $(this).slideDown();
        });
        $(this).addClass('close-cate').removeClass('open-cate').html($(this).data('closetext'));
        e.preventDefault();
    });
    $(document).on('click', '.close-cate', function (e) {
        $(this).closest('.block-nav-category').find('li.link-other').each(function () {
            $(this).slideUp();
        });
        $(this).addClass('open-cate').removeClass('close-cate').html($(this).data('alltext'));
        e.preventDefault();
    });
    $(document).on('click', '.block-nav-category .block-title', function () {
        if ( $(window).width() > 1024 ) {
            $(this).toggleClass('active');
            $(this).parent().toggleClass('has-open');
            $('body').toggleClass('category-open');
        } else {
            $('body').addClass('box-mobile-menu-open');
            $('.ovic-menu-clone-wrap.mobile-vertical-menu').addClass('open');
        }
        return false;
    });
    
    /* END - CATEGORY */
    
    /* SEND EMAIL */
    $(document).on('click', '.send-to-friend', function (e) {
        /*Send to friend*/
        var button     = $(this),
            modal      = $('#kuteshop-modal-popup'),
            product_id = button.data('product_id'),
            data       = {
                security: kuteshop_params.security,
                product_id: product_id
            };
        
        if ( button.hasClass('opened') ) {
            modal.modal('show');
        } else {
            button.addClass('loading');
            $.post(get_url('form_send_friend'), data, function (response) {
                modal.html(response).modal('show');
                button.removeClass('loading');
                button.addClass('opened');
            });
        }
        e.preventDefault();
    });
    $(document).on('click', '#button-send-to-friend', function (e) {
        var button       = $(this),
            friend_name  = $('#friend_name').val(),
            friend_email = $('#friend_email').val(),
            product_id   = button.data('product_id'),
            captcha_code = $('#captcha_code').val(),
            data         = {
                security: kuteshop_params.security,
                friend_name: friend_name,
                friend_email: friend_email,
                product_id: product_id,
                captcha_code: captcha_code
            };
        
        button.addClass('loading');
        
        $.post(get_url('send_email_friend'), data, function (response) {
            
            if ( response.status == 'done' ) {
                //$('#form-send-friend .form').remove();
                $('#friend_email').val('');
                $('#friend_name').val('');
                $('#captcha_code').val('');
                $('#form-send-friend-msg').html('<div class="woocommerce-message">' + response.message + '</div>');
            } else {
                $('#form-send-friend-msg').html('<div class="woocommerce-message woocommerce-error">' + response.message + '</div>');
            }
            $('#captcha_reload').trigger('click');
            
            button.removeClass('loading');
            
        });
        e.preventDefault();
    });
    
    /* NOIFICATIONS */
    
    $.fn.kuteshop_add_notify = function ($text_content) {
        var config    = [],
            $img_html = '',
            $this     = $(this),
            $img      = $this.closest('.product-item').find('img.wp-post-image'),
            pName     = $this.attr('aria-label');
        
        config.duration = kuteshop_params.growl_notice.growl_duration;
        config.title    = kuteshop_params.growl_notice.growl_notice_text;
        
        $this.removeClass('loading');
        
        // if from wishlist
        if ( !$img.length ) {
            $img = $this.closest('tr')
                .find('.product-thumbnail img');
        }
        // if from single product page
        if ( !$img.length ) {
            $img = $this.closest('.single-product').find('.product .woocommerce-product-gallery__wrapper img.wp-post-image');
        }
        // if from default woocommerce
        if ( !$img.length ) {
            $img = $this.closest('.product')
                .find('img');
        }
        if ( typeof pName === 'undefined' || pName === '' ) {
            pName = $this.closest('.product').find('.summary .product_title').text();
        }
        // if from mini cart
        if ( $this.closest('.mini_cart_item').length ) {
            $img  = $this.closest('.mini_cart_item').find('a > img');
            pName = $this.closest('.mini_cart_item').find('a:not(.remove)').clone().children().remove().end().text();
        }
        
        // reset state after 5 sec
        setTimeout(function () {
            $this.removeClass('added').removeClass('recent-added');
            $this.next('.added_to_cart').remove();
        }, 3000, $this);
        
        if ( typeof pName === 'undefined' || pName === '' ) {
            pName = $this.closest('.product-item').find('.product_title a').text().trim();
        }
        
        if ( typeof pName !== 'undefined' && pName !== '' ) {
            var string_start = pName.indexOf("“") + 1,
                string_end   = pName.indexOf("”"),
                pName        = string_start > 1 ? pName.slice(string_start, string_end) : pName;
            
            pName = '<span>' + pName + '</span>';
        } else {
            pName = '';
        }
        
        if ( $img.length ) {
            $img_html = '<figure><img src="' + $img.attr('src') + '"' + ' alt="' + pName + '" class="growl-thumb" /></figure>';
        }
        
        config.message = $img_html + '<p class="growl-content">' + pName + '' + $text_content + '</p>';
        
        $.growl.notice(config);
    };
    
    $(document).on('removed_from_cart', function (event, fragments, cart_hash, $thisbutton) {
        
        $thisbutton.kuteshop_add_notify(
            kuteshop_params.growl_notice.removed_cart_text
        );
        
    });
    
    $(document).on('added_to_cart', function (event, fragments, cart_hash, $thisbutton) {
        
        $thisbutton.kuteshop_add_notify(
            kuteshop_params.growl_notice.added_to_cart_text + '</br>' +
            '<a href="' + wc_add_to_cart_params.cart_url + '">' +
            wc_add_to_cart_params.i18n_view_cart + '</a>'
        );
        
    });
    
    $(document).on('click', '.add_to_wishlist', function () {
        $(this).addClass('loading');
    });
    
    $(document).on('added_to_wishlist', function (event, $thisbutton) {
        
        $thisbutton.kuteshop_add_notify(
            kuteshop_params.growl_notice.added_to_wishlist_text + '</br>' +
            '<a href="' + kuteshop_params.growl_notice.wishlist_url + '">' +
            kuteshop_params.growl_notice.browse_wishlist_text + '</a>'
        );
        
        $thisbutton.removeClass('loading');
        
    });
    
    $(document).on('click', function (event) {
        var $target = $(event.target).closest('#growls-default'),
            $parent = $('#growls-default');
        
        if ( !$target.length ) {
            $('.growl-close').trigger('click');
        }
    });
    
    /* NOIFICATIONS */
    
    $(window).on('wc_fragments_loaded', function () {
        if ( $('.woocommerce-mini-cart').length && isMobile === null && $.fn.scrollbar ) {
            $('.woocommerce-mini-cart').scrollbar();
        }
    });
    
    
    // Open box menu
    $(document).on('click', '.mobile-navigation', function () {
        $('body').addClass('box-mobile-menu-open');
        $('.ovic-menu-clone-wrap.mobile-main-menu').addClass('open');
        return false;
    });
    $(document).on('click', '.mobile-navigation.vertical', function () {
        $('body').addClass('box-mobile-menu-open');
        $('.ovic-menu-clone-wrap.mobile-vertical-menu').addClass('open');
        return false;
    });
    // Close box menu
    $(document).on('click', '.ovic-menu-clone-wrap .ovic-menu-close-panels', function () {
        $('body').removeClass('box-mobile-menu-open');
        $('.ovic-menu-clone-wrap').removeClass('open');
        return false;
    });
    // Toggle box menu
    $(document).on('click', function (event) {
        var menu_mobile = $('.ovic-menu-clone-wrap');
        if ( $('body').hasClass('rtl') ) {
            if ( event.offsetX < 0 ) {
                menu_mobile.removeClass('open');
                $('body').removeClass('box-mobile-menu-open');
            }
        } else {
            if ( event.offsetX > menu_mobile.width() ) {
                menu_mobile.removeClass('open');
                $('body').removeClass('box-mobile-menu-open');
            }
        }
    });
    
    $(document).on('click', '.more_seller_product_tab > a', function () {
        var $tab_id    = $(this).attr('href'),
            $container = $($tab_id).find('.equal-container.better-height');
        
        if ( $container.length ) {
            $container.kuteshop_better_equal_elems();
        }
    });
    
    $(document).on('wc-product-gallery-after-init', function (event, target) {
        if ( $(target).find('.flex-control-thumbs').length ) {
            $(target).find('.flex-control-thumbs').kuteshop_product_thumbnail();
        }
    });
    
    window.addEventListener("load", function load() {
        /**
         * remove listener, no longer needed
         * */
        window.removeEventListener("load", load, false);
        /**
         * start functions
         * */
        if ( $('.lazy').length ) {
            $('.lazy').kuteshop_init_lazy_load();
        }
        if ( $('.owl-slick').length ) {
            $('.owl-slick').kuteshop_init_carousel();
        }
        if ( $('.kuteshop-countdown').length ) {
            $('.kuteshop-countdown').kuteshop_countdown();
        }
        if ( $('.equal-container.better-height').length ) {
            $('.equal-container.better-height').kuteshop_better_equal_elems();
        }
        if ( $('.widget_product_categories .product-categories').length ) {
            $('.widget_product_categories .product-categories').kuteshop_category_product();
        }
        if ( $('.category-search-option').length ) {
            $('.category-search-option').chosen();
        }
        if ( $('#popup-newsletter').length ) {
            $('#popup-newsletter').kuteshop_init_popup();
        }
        /* SCROLLBAR */
        if ( isMobile === null ) {
            if ( $('.block-nav-category').length ) {
                $('.block-nav-category').kuteshop_category_vertical();
            }
            if ( $('.verticalmenu-content').length ) {
                $('.verticalmenu-content').kuteshop_auto_width_vertical_menu();
            }
            if ( $('.header-sticky-menu').length && $(window).width() > 1024 ) {
                $('.header-sticky-menu').kuteshop_sticky_header();
            }
            if ( $('.single_add_to_cart_button').length ) {
                $('.single_add_to_cart_button').kuteshop_alert_variable_product();
            }
        }
        $('body').kuteshop_jump_section();
    }, false);
    
	
	
})(window.jQuery);
