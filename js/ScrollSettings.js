$(document).ready(function () {
    $.fn.fullpage({
        verticalCentered: true,
        resize: true,
        /*slidesColor : ['black', 'red', 'blue', 'green', 'pink', 'orange'],*/
        anchors: ['firstPage', 'secondPage', '3rdPage', '4thPage', '5thPage', '6thPage'],
        scrollingSpeed: 700,
        easing: 'easeInQuart',
        menu: '#menu',
        navigation: false,
        navigationPosition: 'right',
        navigationTooltips: ['firstSlide', 'secondSlide'],
        slidesNavigation: true,
        slidesNavPosition: 'bottom',
        loopBottom: false,
        loopTop: false,
        loopHorizontal: true,
        autoScrolling: true,
        scrollOverflow: false,
        css3: false,
        paddingTop: '0',
        paddingBottom: '0',
        fixedElements: '#element1, .element2',
        normalScrollElements: '#element1, .element2',
        keyboardScrolling: true,
        touchSensitivity: 15,
        continuousVertical: false,
        animateAnchor: true,
        controlArrowColor: '#d3291e',

        //events
        onLeave: function (index, direction) { },
        afterLoad: function (anchorLink, index) { },
        afterRender: function () { },
        afterSlideLoad: function (anchorLink, index, slideAnchor, slideIndex) { },
        onSlideLeave: function (anchorLink, index, slideIndex, direction) { }
    });
});