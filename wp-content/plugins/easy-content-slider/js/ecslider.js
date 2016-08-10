;(function($){
    $.fn.ecslider = function(options) {

        var defaults = {
            mainClassName : 'ecslider',
            listPosition : 'right',
            selectionMode : 'click',
            transitionEffect : 'fading',
            autoSlide : true,
            displayList : true,
            displayControls : false,
            touchControls : true,
            verticalCentering : false,
            adaptiveHeight : false,
            maxHeight : null,
            beforeSlide : null,
            afterSlide : null,
            adaptiveDuration : 200,
            transitionDuration : 500,
            intervalDuration : 3000
        };

        if (this.length == 0) {
            return this;
        } else if(this.length > 1) {
            this.each(function() {
                $(this).ecslider(options);
            });
            return this;
        }

        var ecslider = this;
        ecslider.plugin = this;
        ecslider.data = [];
        ecslider.config = {};
        ecslider.currentSlide = 0;
        ecslider.slideCount = 0;
        ecslider.resizeEvent = null;
        ecslider.intervalEvent = null;
        ecslider.touchFirstPosition = null;
        ecslider.transitionInProgress = false;
        ecslider.window = $(window);

        // Init
        var init = function() {

            // Merge user options with the default configuration
            ecslider.config = $.extend({}, defaults, options);

            // Setup
            setup();

            // Activate interval
            if (ecslider.config.autoSlide) {
                activateInterval();
            }

            return true;
        };

        // Get element
        var getElement = function(obj) {
            var element = {};

            // Get link
            var elementLink = obj.find('a').attr('href');
            if ((typeof elementLink != 'undefined') && (elementLink != '')) {
                element.link = elementLink;
                var elementLinkTarget = obj.find('a').attr('target');
                if ((typeof elementLinkTarget != 'undefined') && (elementLinkTarget != '')) {
                    element.linkTarget = elementLinkTarget;
                }
            }

            // Get image 
            var elementThumbnail = obj.find('img').attr('src');
            if ((typeof elementThumbnail != 'undefined') && (elementThumbnail != '')) {
                element.thumbnail = elementThumbnail;
            }

            var elementImage = obj.find('img').attr('data-large-src');
            if ((typeof elementImage != 'undefined') && (elementImage != '')) {
                element.image = elementImage;
            }

            // Get title 
            var elementSpan = obj.find('span').text();
            if ((typeof elementSpan != 'undefined') && (elementSpan != '') && (elementSpan != null)) {
                element.title = elementSpan;
            } else {
                var elementTitle = obj.find('img').attr('alt');
                if ((typeof elementTitle != 'undefined') && (elementTitle != '')) {
                    element.title = elementTitle;
                }
            }

            // Get description
            var elementDescription = obj.find('img').attr('data-description');
            if ((typeof elementDescription != 'undefined') && (elementDescription != '')) {
                element.description = elementDescription;
            }

            return element;
        };

        // Update the current height
        var updateHeight = function(height, animate) {

            // Check maxHeight
            if (ecslider.config.maxHeight) {
                if (ecslider.plugin.width() > 480 && height > ecslider.config.maxHeight) {
                    height = ecslider.config.maxHeight;
                } else if (ecslider.plugin.width() <= 480) {
                    if (height + ecslider.plugin.find('.ps-list').height() > ecslider.config.maxHeight) {
                        height = ecslider.config.maxHeight - ecslider.plugin.find('.ps-list').height();
                    }
                }
            }

            // Prevents multiple calculations in a short time
            clearTimeout(ecslider.resizeEvent);
            ecslider.resizeEvent = setTimeout(function() {

                // Adjust right list
                var elementHeight = ((height - ((ecslider.slideCount - 1) * 6)) / ecslider.slideCount);
                var elementWidth = (100 / ecslider.slideCount);
                ecslider.plugin.find('.ps-list > li').css({ width: elementWidth + '%' });

                // Adjust main container
                if (typeof animate != 'undefined' && animate && ecslider.config.maxHeight == null) {

                    if (typeof ecslider.plugin.find('.ps-current').animate == 'function') {
                        ecslider.plugin.find('.ps-current').animate({
                            height: height
                        }, ecslider.config.adaptiveDuration, function() {
                            ecslider.plugin.find('.ps-list > li').animate({ height: elementHeight }, ecslider.config.adaptiveDuration);
                        });
                    } else {
                        ecslider.plugin.find('.ps-current').css('height', height);
                        ecslider.plugin.find('.ps-list > li').css('height', elementHeight);
                    }

                } else {
                    ecslider.plugin.find('.ps-current').css('height', height);
                    ecslider.plugin.find('.ps-list > li').css('height', elementHeight);
                }

                // Vertical alignement
                if (ecslider.config.verticalCentering) {

                    // List elements
                    ecslider.plugin.find('.ps-list > li').each(function(){
                        if ((elementHeight > 50) && ($(this).find('img').height() > elementHeight)) {
                            var imageMargin = Math.round(($(this).find('img').height() - elementHeight) / 2);
                            $(this).find('img').css('margin-top', -imageMargin);

                        } else if ($(this).find('img').height() < elementHeight) {
                            var imageMargin = Math.round((elementHeight - $(this).find('img').height()) / 2);
                            $(this).find('img').css('margin-top', imageMargin);

                        } else {
                            $(this).find('img').css('margin-top', '');
                        }
                    });

                    // Current elements
                    ecslider.plugin.find('.ps-current > ul > li').each(function(){
                        var isVisible = ($(this).css('display') == 'none') ? false : true;

                        if (! isVisible) {
                            $(this).show();
                        }

                        if ($(this).show().find('img').height() > height) {
                            var imageMargin = Math.round(($(this).find('img').height() - height) / 2);
                            $(this).find('img').css('margin-top', -imageMargin);

                        } else if ($(this).show().find('img').height() < height) {
                            var imageMargin = Math.round((height - $(this).find('img').height()) / 2);
                            $(this).find('img').css('margin-top', imageMargin);

                        } else {
                            $(this).find('img').css('margin-top', '');
                        }

                        if (! isVisible) {
                            $(this).hide();
                        }
                    });
                }

            }, 100);

            return true;
        };

        // Set size class
        var setSizeClass = function() {

            if (ecslider.plugin.width() <= 480) {
                ecslider.plugin.addClass('narrow').removeClass('wide');
            } else {
                ecslider.plugin.addClass('wide').removeClass('narrow');
            }

            return true;
        };

        // Setup
        var setup = function() {

            // Create container
            ecslider.plugin.removeClass(ecslider.config.mainClassName).addClass('ps-list');
            ecslider.plugin.wrap('<div class="' + ecslider.config.mainClassName + '"></div>');
            ecslider.plugin = ecslider.plugin.parent();
            ecslider.plugin.prepend('<div class="ps-current"><ul></ul><span class="ps-caption"></span></div>');
            ecslider.slideCount = ecslider.plugin.find('.ps-list > li').length;

            if (ecslider.slideCount == 0) {
                throw new Error('EcSlider - No slider item has been found');
                return false;
            }

            // Add controls
            if (ecslider.config.displayControls && ecslider.slideCount > 1) {
                ecslider.plugin.find('.ps-current').prepend('<span class="ps-prev"><span class="ps-prevIcon"></span></span>');
                ecslider.plugin.find('.ps-current').append('<span class="ps-next"><span class="ps-nextIcon"></span></span>');
                ecslider.plugin.find('.ps-current .ps-prev').click(function() {
                    ecslider.previousSlide();
                });
                ecslider.plugin.find('.ps-current .ps-next').click(function() {
                    ecslider.nextSlide();
                });
            }

            // Disable list
            if (! ecslider.config.displayList) {
                ecslider.plugin.find('.ps-current').css('width', '100%');
                ecslider.plugin.find('.ps-list').hide();
            }

            // Get slider elements
            var elementId = 1;
            ecslider.plugin.find('.ps-list > li').each(function() {
                var element = getElement($(this));
                element.id = elementId;
                ecslider.data.push(element);

                $(this).addClass('elt_' + element.id);

                // Check element title
                if (element.title) {
                    if ($(this).find('span').length == 1) {
                        if ($(this).find('span').text() == '') {
                            $(this).find('span').text(element.title);
                        }
                    } else {
                        $(this).find('img').after('<span>' + element.title + '</span>');
                    }
                }

                // Set element in the current list
                var currentElement = $('<li class="elt_' + elementId + '"></li>');

                if (element.image) {
                    currentElement.html('<img src="' + element.image + '" alt="' + (element.title ? element.title : '') + '">');
                } else if (element.thumbnail) {
                    currentElement.html('<img src="' + element.thumbnail + '" alt="' + (element.title ? element.title : '') + '">');
                }

                if (element.link) {
                    currentElement.html('<a href="' + element.link + '"' + (element.linkTarget ? ' target="' + element.linkTarget + '"' : '') + '>' + currentElement.html() + '</a>');
                }

                ecslider.plugin.find('.ps-current > ul').append(currentElement);

                // Set selection mode
                if ((ecslider.config.selectionMode == 'mouseOver') && (ecslider.config.transitionEffect == 'fading')) {
                    $(this).css('cursor', 'default').click(function(event) {
                        event.preventDefault();
                    }).bind('mouseenter', function(event) {
                        displayElement(element.id);
                    });
                    $(this).find('a').css('cursor', 'default');
                } else {
                    $(this).css('cursor', 'pointer').click(function(event) {
                        event.preventDefault();
                        displayElement(element.id);
                    });
                }

                elementId++;
            });

            // Set list position
            if (ecslider.config.listPosition == 'left') {
                ecslider.plugin.addClass('listOnTheLeft');
            }

            // Attach slide events
            if (ecslider.config.autoSlide) {
                ecslider.plugin.on('mouseenter', function() {
                    clearInterval(ecslider.intervalEvent);
                    ecslider.intervalEvent = null;
                }).on('mouseleave', function() {
                    activateInterval();
                });
            }

            // Display the first element
            displayElement(1);

            // Set the first height
            ecslider.plugin.find('.ps-current > ul > li.elt_1 img').on('load', function() {
                setSizeClass();

                var maxHeight = ecslider.plugin.find('.ps-current > ul > li.elt_1 img').height();
                updateHeight(maxHeight);

                ecslider.window.resize(function() {
                    // The new class must be set before the recalculation of the height.
                    setSizeClass();

                    var maxHeight = ecslider.plugin.find('.ps-current > ul > li.elt_' + ecslider.currentSlide + ' img').height();
                    updateHeight(maxHeight, ecslider.config.adaptiveHeight);
                });
            });

            // Touch controls for current image
            if (ecslider.config.touchControls && ecslider.slideCount > 1) {

                ecslider.plugin.find('.ps-current').on('touchstart', function(e) {
                    try {
                        if (e.originalEvent.touches[0].clientX && ecslider.touchFirstPosition == null) {
                            ecslider.touchFirstPosition = e.originalEvent.touches[0].clientX;
                        }
                    } catch(e) {
                        ecslider.touchFirstPosition = null;
                    }
                });

                ecslider.plugin.find('.ps-current').on('touchmove', function(e) {
                    try {
                        if (e.originalEvent.touches[0].clientX && ecslider.touchFirstPosition != null) {
                            if (e.originalEvent.touches[0].clientX > (ecslider.touchFirstPosition + 50)) {
                                ecslider.touchFirstPosition = null;
                                ecslider.previousSlide();
                            } else if (e.originalEvent.touches[0].clientX < (ecslider.touchFirstPosition - 50)) {
                                ecslider.touchFirstPosition = null;
                                ecslider.nextSlide();
                            }
                        }
                    } catch(e) {
                        ecslider.touchFirstPosition = null;
                    }
                });

                ecslider.plugin.find('.ps-current').on('touchend', function(e) {
                    ecslider.touchFirstPosition = null;
                });
            }

            return true;
        };

        // Finish element
        var finishElement = function(element) {

            // Element caption
            var elementText = '';
            if (element.title) {
                elementText += '<b>' + element.title + '</b>';
            }

            if (element.description) {
                if (elementText != '') elementText += '<br>';
                elementText += element.description;
            }

            if (elementText != '') {
                if (element.link) {
                    elementText = '<a href="' + element.link + '"' + (element.linkTarget ? ' target="' + element.linkTarget + '"' : '') + '>' + elementText + '</a>';
                }

                if (typeof ecslider.plugin.find('.ps-caption').fadeIn == 'function') {
                    ecslider.plugin.find('.ps-caption').html(elementText);
                    ecslider.plugin.find('.ps-caption').fadeIn(ecslider.config.transitionDuration / 2);
                } else {
                    ecslider.plugin.find('.ps-caption').html(elementText);
                    ecslider.plugin.find('.ps-caption').show();
                }
            }

            // Slider controls
            if (ecslider.config.displayControls) {
                if (typeof ecslider.plugin.find('.ps-current > .ps-prev').fadeIn == 'function') {
                    ecslider.plugin.find('.ps-current > .ps-prev, .ps-current > .ps-next').fadeIn(ecslider.config.transitionDuration / 2);
                } else {
                    ecslider.plugin.find('.ps-current > .ps-prev, .ps-current > .ps-next').show();
                }
            }

            // After slide
            if (typeof ecslider.config.afterSlide == 'function') {
                ecslider.config.afterSlide(element.id);
            }

            // Set the container height
            if (ecslider.config.adaptiveHeight) {
                var maxHeight = ecslider.plugin.find('.ps-current .elt_' + element.id + ' img').height();
                updateHeight(maxHeight, true);
            }

            return true;
        }

        // Fade an element
        var fadeElement = function(element) {
            var elementContainer = ecslider.plugin.find('.ps-current > ul');

            // Update list items
            ecslider.plugin.find('.ps-list > li').css('opacity', '0.6');
            ecslider.plugin.find('.ps-list > li.elt_' + element.id).css('opacity', '1');

            elementContainer.find('li').not('.elt_' + ecslider.currentSlide).not('.elt_' + element.id).each(function(){
                if (typeof $(this).stop == 'function') {
                    $(this).stop();
                }
                $(this).css('position', '').css('z-index', 1).hide();
            });

            // Current element
            if (ecslider.currentSlide > 0) {
                var currentElement = elementContainer.find('.elt_' + ecslider.currentSlide);

                if (typeof currentElement.animate != 'function') {
                    currentElement.animate = function(css, duration, callback) {
                        currentElement.css(css);
                        if (callback) {
                            callback();
                        }
                    };
                }

                if (typeof currentElement.stop == 'function') {
                    currentElement.stop();
                }

                currentElement.css('position', 'absolute').animate({
                    opacity : 0,
                }, ecslider.config.transitionDuration, function() {
                    currentElement.css('position', '').css('z-index', 1).hide();
                });
            }

            // Update current id
            ecslider.currentSlide = element.id;

            // Next element
            var nextElement = elementContainer.find('.elt_' + element.id);

            if (typeof nextElement.animate != 'function') {
                nextElement.animate = function(css, duration, callback) {
                    nextElement.css(css);
                    if (callback) {
                        callback();
                    }
                };
            }

            if (typeof nextElement.stop == 'function') {
                nextElement.stop();
            }

            nextElement.css('position', 'absolute').show().animate({
                opacity : 1,
            }, ecslider.config.transitionDuration, function() {
                nextElement.css('position', '').css('z-index', 2).show();
                finishElement(element);
            });

            return true;
        }

        // Slide an element
        var slideElement = function(element, direction) {
            var elementContainer = ecslider.plugin.find('.ps-current > ul');

            if (typeof direction == 'undefined') {
                direction = 'left';
            }

            if (ecslider.currentSlide == 0) {
                elementContainer.find('.elt_1').css({
                    position : '',
                    left : '',
                    opacity : 1,
                    'z-index' : 2
                }).show();
                ecslider.plugin.find('.ps-list > li.elt_1').css('opacity', '1');
                finishElement(element);

            } else {

                if (ecslider.transitionInProgress) {
                    return false;
                }

                ecslider.transitionInProgress = true;

                // Get direction details
                var elementWidth = elementContainer.width();

                if (direction == 'left') {
                    var elementDest = -elementWidth;
                    var nextOrigin = elementWidth;
                } else {
                    var elementDest = elementWidth;
                    var nextOrigin = -elementWidth;
                }

                var currentElement = elementContainer.find('.elt_' + ecslider.currentSlide);

                if (typeof currentElement.animate != 'function') {
                    currentElement.animate = function(css, duration, callback) {
                        currentElement.css(css);
                        if (callback) {
                            callback();
                        }
                    };
                }

                currentElement.css('position', 'absolute').animate({
                    left : elementDest,
                }, ecslider.config.transitionDuration, function() {
                    currentElement.css('position', '').css('z-index', 1).css('left', '').css('opacity', 0).hide();
                });

                // Next element
                var nextElement = elementContainer.find('.elt_' + element.id);

                if (typeof nextElement.animate != 'function') {
                    nextElement.animate = function(css, duration, callback) {
                        nextElement.css(css);
                        if (callback) {
                            callback();
                        }
                    };
                }

                nextElement.css('position', 'absolute').css('left', nextOrigin).css('opacity', 1).show().animate({
                    left : 0,
                }, ecslider.config.transitionDuration, function() {
                    nextElement.css('position', '').css('left', '').css('z-index', 2).show();
                    ecslider.transitionInProgress = false;

                    // Display new element
                    ecslider.plugin.find('.ps-list > li').css('opacity', '0.6');
                    ecslider.plugin.find('.ps-list > li.elt_' + element.id).css('opacity', '1');

                    finishElement(element);
                });
            }

            // Update current id
            ecslider.currentSlide = element.id;

            return true;
        }

        // Display the current element
        var displayElement = function(elementId, apiController, direction) {

            if (elementId == ecslider.currentSlide) {
                return false;
            }

            var element = ecslider.data[elementId - 1];

            if (typeof element == 'undefined') {
                throw new Error('EcSlider - The element ' + elementId + ' is undefined');
                return false;
            }

            if (typeof direction == 'undefined') {
                direction = 'left';
            }

            // Before slide
            if (typeof ecslider.config.beforeSlide == 'function') {
                ecslider.config.beforeSlide(elementId);
            }

            if (typeof ecslider.plugin.find('.ps-caption').fadeOut == 'function') {
                ecslider.plugin.find('.ps-caption, .ps-prev, .ps-next').fadeOut(ecslider.config.transitionDuration / 2);
            } else {
                ecslider.plugin.find('.ps-caption, .ps-prev, .ps-next').hide();
            }

            // Choose the transition effect
            if (ecslider.config.transitionEffect == 'sliding') {
                slideElement(element, direction);
            } else {
                fadeElement(element);
            }

            // Reset interval to avoid a half interval after an API control
            if (typeof apiController != 'undefined' && ecslider.config.autoSlide) {
                activateInterval();
            }

            return true;
        };

        // Activate interval
        var activateInterval = function() {
            clearInterval(ecslider.intervalEvent);

            if (ecslider.slideCount > 1 && ecslider.config.autoSlide) {
                ecslider.intervalEvent = setInterval(function() {
                    if (ecslider.currentSlide + 1 <= ecslider.slideCount) {
                        var nextItem = ecslider.currentSlide + 1;
                    } else {
                        var nextItem = 1;
                    }
                    displayElement(nextItem);
                }, ecslider.config.intervalDuration);
            }

            return true;
        };

        // Start auto slide
        ecslider.startSlide = function() {
            ecslider.config.autoSlide = true;
            activateInterval();
            return true;
        };

        // Stop auto slide
        ecslider.stopSlide = function() {
            ecslider.config.autoSlide = false;
            clearInterval(ecslider.intervalEvent);
            return true;
        };

        // Get current slide
        ecslider.getCurrentSlide = function() {
            return ecslider.currentSlide;
        };

        // Get slide count
        ecslider.getSlideCount = function() {
            return ecslider.slideCount;
        };

        // Display slide
        ecslider.displaySlide = function(itemId) {
            displayElement(itemId, true);
            return true;
        };

        // Next slide
        ecslider.nextSlide = function() {
            if (ecslider.currentSlide + 1 <= ecslider.slideCount) {
                var nextItem = ecslider.currentSlide + 1;
            } else {
                var nextItem = 1;
            }
            displayElement(nextItem, true, 'left');
            return true;
        };

        // Previous slide
        ecslider.previousSlide = function() {
            if (ecslider.currentSlide - 1 >= 1) {
                var previousItem = ecslider.currentSlide - 1;
            } else {
                var previousItem = ecslider.slideCount;
            }
            displayElement(previousItem, true, 'right');
            return true;
        };

        // Destroy slider
        ecslider.destroy = function(soft) {
            clearInterval(ecslider.intervalEvent);

            if (typeof soft != 'undefined') {
                ecslider.plugin.find('.ps-list > li').each(function() {
                    $(this).attr('style', null).removeClass().css('cursor', '').unbind('click').unbind('mouseenter');
                    $(this).find('a').css('cursor', '');
                    $(this).find('img').attr('style', null);
                });

                ecslider.plugin.find('.ps-list').addClass(ecslider.config.mainClassName).removeClass('ps-list');
                ecslider.plugin.find('.ps-current').unwrap().remove();
                ecslider.hide();

            } else {
                ecslider.parent().remove();
            }

            ecslider.plugin = null;
            ecslider.data = [];
            ecslider.config = {};
            ecslider.currentSlide = 0;
            ecslider.slideCount = 0;
            ecslider.resizeEvent = null;
            ecslider.intervalEvent = null;
            ecslider.touchFirstPosition = null;
            ecslider.transitionInProgress = false;
            ecslider.window = null;

            return true;
        };

        // Reload slider
        ecslider.reload = function(newOptions) {
            ecslider.destroy(true);

            ecslider = this;
            ecslider.plugin = this;
            ecslider.window = $(window);
            ecslider.plugin.show();

            // Merge new options with the default configuration
            ecslider.config = $.extend({}, defaults, newOptions);

            // Setup
            setup();

            // Activate interval
            if (ecslider.config.autoSlide) {
                activateInterval();
            }

            return true;
        };

        // Slider initialization
        init();

        return this;
    }
})(window.Zepto || window.jQuery);
