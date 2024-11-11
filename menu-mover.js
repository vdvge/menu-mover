jQuery(document).ready(function ($) {
    let lastScrollTop = 0;
    const $menu = $(menuMoverOptions.selector);
    const scrollThreshold = 10; // Schwelle für das Scrollen in Pixeln

    // Menü-Höhe und Geschwindigkeit ermitteln
    const menuHeight = $menu.outerHeight();
    const speed = menuMoverOptions.speed + "ms"; // Geschwindigkeit in ms

    if ($menu.length) {
        // CSS-Übergang basierend auf der Geschwindigkeit einstellen
        $menu.css("transition", `transform ${speed} ease`);

        $(window).on("scroll", function () {
            let scrollTop = $(this).scrollTop();

            // Prüfen, ob die Scroll-Differenz die Schwelle überschreitet
            if (Math.abs(scrollTop - lastScrollTop) > scrollThreshold) {
                if (scrollTop > lastScrollTop) {
                    // Nach unten scrollen - Menü nach oben aus dem Viewport schieben
                    $menu.css("transform", `translateY(-${menuHeight}px)`);
                } else {
                    // Nach oben scrollen - Menü wieder in den Viewport schieben
                    $menu.css("transform", `translateY(0px)`);
                }
                lastScrollTop = scrollTop; // Letzten Scroll-Top-Wert aktualisieren
            }
        });
    } else {
        console.log("Menü-Selektor wurde nicht gefunden:", menuMoverOptions.selector);
    }
});
