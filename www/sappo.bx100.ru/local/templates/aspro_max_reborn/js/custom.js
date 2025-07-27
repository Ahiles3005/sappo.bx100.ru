/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/


let data = new FormData();
data.append("uri", window.location.href);

fetch("/ajax/ssrRegions.php", {
    method: "POST",
    body: data,
}).then( response => {
    if (response.status !== 200) {

        return Promise.reject();
    }
    return response.json()
})
    .then(function (res){
        $('.js-ssr-mobileRegions').replaceWith(res.mobile_html)
        $(document).on("click", ".mobile_regions .city_item", function (e) {
            e.preventDefault();
            var _this = $(this);
            $.removeCookie("current_region");
            $.cookie("current_region", _this.data("id"), { path: "/", domain: arAsproOptions["SITE_ADDRESS"] });
            location.href = _this.attr("href");
        });
    })
    .catch(() => console.log('ошибка'));



document.addEventListener("DOMContentLoaded", function () {


    /* ---------- ********** HEADER ********** ---------- */

    if (document.querySelector(".c-header--div__TOP")) {

        // ФИКСАЦИЯ СРЕДНЕЙ ЧАСТИ ШАПКИ

        const headerMiddle = document.querySelector(".c-header--div__FIXED");

        if (window.scrollY > 50) {
            headerMiddle.classList.add("__c-header--div__FIXED");
        } else {
            headerMiddle.classList.remove("__c-header--div__FIXED");
        }
        window.addEventListener("scroll", () => {
            if (window.scrollY > 50) {
                headerMiddle.classList.add("__c-header--div__FIXED");
            } else {
                headerMiddle.classList.remove("__c-header--div__FIXED");
            }
        });

    }







    /* ---------- ********** FOOTER ********** ---------- */

    if (document.querySelector(".c-footer")) {

        // РАСКРЫВАЮЩЕЕСЯ МОБИЛЬНОЕ МЕНЮ

        const footerMobPTitle = Array.from(document.querySelectorAll(".c-footerMob--p__TITLE"));
        const footerMobSvgTitle = Array.from(document.querySelectorAll(".c-footerMob--svg__TITLE"));
        const footerMobUl = Array.from(document.querySelectorAll(".c-footerMob--ul"));


        footerMobPTitle.forEach((v, i, a) => {
            a[i].addEventListener("click", () => {
                footerMobSvgTitle[i].classList.toggle("__c-footerMob--svg__TITLE");
                footerMobUl[i].classList.toggle("__c-footerMob--ul");
            });
        });





        // ВЫБОР КОНТАКТОВ В ЗАВИСИМОСТИ ОТ ВЫБРАННОГО РЕГИОНА
        // (ПРИ НЕОБХОДИМОСТИ АКТИВИРОВАТЬ ЭТОТ ВЫБОР -
        // РАСКОММЕНТИРОВАТЬ ВЫЗОВ ФУНКЦИИ changeFooterContacts)

        function changeFooterContacts () {

            const cFooterPhone = Array.from (document.querySelectorAll (".c-footer--a__PHONE"));
            const cFooterMail = Array.from (document.querySelectorAll (".c-footer--a__MAIL"));
            const regChosen = document.querySelector (".c-header--div__TOP_ITEM .js_city_chooser > i + span");
            const regsNames = [
                "Москва",
                "Апрелевка",
                "Балашиха",
                "Бронницы",
                "Верея",
                "Видное",
                "Волоколамск",
                "Воскресенск",
                "Высоковск",
                "Голицыно",
                "Дедовск",
                "Дзержинский",
                "Дмитров",
                "Долгопрудный",
                "Домодедово",
                "Дрезна",
                "Дубна",
                "Егорьевск",
                "Жуковский",
                "Зарайск",
                "Звенигород",
                "Зеленоград",
                "Ивантеевка",
                "Истра",
                "Кашира",
                "Клин",
                "Коломна",
                "Королёв",
                "Котельники",
                "Красноармейск",
                "Красногорск",
                "Краснозаводск",
                "Краснознаменск",
                "Кубинка",
                "Куровское",
                "Ликино-Дулёво",
                "Лобня",
                "Лосино-Петровский",
                "Луховицы",
                "Лыткарино",
                "Люберцы",
                "Можайск",
                "Московский",
                "Мытищи",
                "Наро-Фоминск",
                "Ногинск",
                "Одинцово",
                "Озёры",
                "Орехово-Зуево",
                "Павловский Посад",
                "Пересвет",
                "Подольск",
                "Протвино",
                "Пушкино",
                "Пущино",
                "Раменское",
                "Реутов",
                "Рошаль",
                "Руза",
                "Сергиев Посад",
                "Серпухов",
                "Солнечногорск",
                "Старая Купавна",
                "Ступино",
                "Талдом",
                "Троицк",
                "Фрязино",
                "Химки",
                "Хотьково",
                "Черноголовка",
                "Чехов",
                "Шатура",
                "Щёлково",
                "Щербинка",
                "Электрогорск",
                "Электросталь",
                "Электроугли",
                "Яхрома"
            ];


            if (regsNames.find (x => x === regChosen.textContent)) {
                cFooterPhone[0].classList.remove ("hid");
                cFooterPhone[2].classList.remove ("hid");
                cFooterPhone[1].classList.add ("hid");
                cFooterPhone[3].classList.add ("hid");

                cFooterMail[0].classList.remove ("hid");
                cFooterMail[2].classList.remove ("hid");
                cFooterMail[1].classList.add ("hid");
                cFooterMail[3].classList.add ("hid");
            } else {
                cFooterPhone[0].classList.add ("hid");
                cFooterPhone[2].classList.add ("hid");
                cFooterPhone[1].classList.remove ("hid");
                cFooterPhone[3].classList.remove ("hid");

                cFooterMail[0].classList.add ("hid");
                cFooterMail[2].classList.add ("hid");
                cFooterMail[1].classList.remove ("hid");
                cFooterMail[3].classList.remove ("hid");
            }

        }

        // changeFooterContacts ();

    }














    /* ---------- ////////// ********** ДОМАШНЯЯ СТРАНИЦА ********** ////////// ---------- */


    /* ---------- ********** СЕКЦИЯ BANNERS ********** ---------- */


    if (document.querySelector(".hm-banners--div__WRAPPER")) {

        // 1 Слайдер
        const hmBannerSwiper1 = new Swiper(".hm-banners--div__SWIPER1", {
            loop: true,
            grabCursor: true,
            watchOverflow: true,
            slidesPerView: 1,
            speed: 1000,
            autoplay: {
                delay: 4000,
            },
            navigation: {
                nextEl: '.hm-banners--button__SWIPER1_NEXT',
                prevEl: '.hm-banners--button__SWIPER1_PREV',
            },
            pagination: {
                el: '.hm-banners--div__SWIPER1_PAG',
                type: 'bullets',
                clickable: true,
            },
            on: {
                init() {
                    this.el.addEventListener('mouseenter', () => {
                        this.autoplay.stop();
                    });

                    this.el.addEventListener('mouseleave', () => {
                        this.autoplay.start();
                    });
                }
            },
        });


        // 2 Слайдер
        const hmBannerSwiper2 = new Swiper(".hm-banners--div__SWIPER2", {
            grabCursor: true,
            watchOverflow: true,
            speed: 1000,
            navigation: {
                nextEl: '.hm-banners--button__SWIPER2_NEXT',
                prevEl: '.hm-banners--button__SWIPER2_PREV',
            },
            breakpoints: {
                200: {
                    slidesPerView: "auto",
                    spaceBetween: 16,
                },
                1200: {
                    slidesPerView: "auto",
                    spaceBetween: 16,
                },
                1300: {
                    slidesPerView: "auto",
                    spaceBetween: 32,
                },
                5000: {
                    slidesPerView: "auto",
                    spaceBetween: 32,
                }
            },
        });

    }







    /* ---------- ********** СЕКЦИЯ NEWS ********** ---------- */


    if (document.querySelector(".hm-news")) {

        // ТАБЫ

        const hmNewsTop = Array.from(document.querySelectorAll(".hm-news--div__TOP"));
        const hmNewsBody = Array.from(document.querySelectorAll(".hm-news--div__BODY"));


        hmNewsTop.forEach((v, i, a) => {
            a[i].addEventListener("click", () => {
                a[i].classList.add("__hm-news--div__TOP");
                hmNewsTop.filter(x => x !== a[i]).forEach((v, i, a) => {
                    a[i].classList.remove("__hm-news--div__TOP");
                });


                hmNewsBody[i].classList.add("__hm-news--div__BODY");
                setTimeout(() => {
                    hmNewsBody[i].classList.add("__opac");
                }, 1);
                hmNewsBody.filter(x => x !== hmNewsBody[i]).forEach((v, i, a) => {
                    a[i].classList.remove("__hm-news--div__BODY");
                });
                hmNewsBody.filter(x => x !== hmNewsBody[i]).forEach((v, i, a) => {
                    a[i].classList.remove("__opac");
                });
            });
        });
    }







    /* ---------- ********** СЕКЦИЯ INFO ********** ---------- */


    if (document.querySelector(".hm-info")) {

        // РАСКРЫТИЕ СЕКЦИИ

        const hmInfoButton = document.querySelector (".hm-info--button__ALL");
        const hmInfoMore = document.querySelector (".hm-info--div__BODY");


        hmInfoButton.addEventListener ("click", () => {
            hmInfoButton.classList.add ("__hm-info--button__ALL");
            setTimeout (() => {
                hmInfoButton.classList.add ("__hm-info--button__ALL1");
            }, 700);
            hmInfoMore.classList.add ("__hm-info--div__BODY");
        });

    }







    /* ---------- ********** СЕКЦИЯ BRANDS ********** ---------- */


    if (document.querySelector(".hm-brands")) {

        const hmBrandsSwiper1 = new Swiper(".hm-brands--div__SWIPER1", {
            loop: true,
            grabCursor: true,
            watchOverflow: true,
            speed: 1000,
            autoplay: {
                delay: 4000,
            },
            navigation: {
                nextEl: '.hm-brands--button__SWIPER1_NEXT',
                prevEl: '.hm-brands--button__SWIPER1_PREV',
            },
            breakpoints: {
                200: {
                    slidesPerView: "auto",
                    spaceBetween: 12,
                },
                1200: {
                    slidesPerView: "auto",
                    spaceBetween: 16,
                },
                1300: {
                    slidesPerView: "auto",
                    spaceBetween: 24,
                },
                5000: {
                    slidesPerView: "auto",
                    spaceBetween: 24,
                }
            },
            on: {
                init() {
                    this.el.addEventListener('mouseenter', () => {
                        this.autoplay.stop();
                    });

                    this.el.addEventListener('mouseleave', () => {
                        this.autoplay.start();
                    });
                }
            },
        });


        const hmBrandsSwiper2 = new Swiper(".hm-brands--div__SWIPER2", {
            loop: true,
            grabCursor: true,
            watchOverflow: true,
            speed: 1000,
            autoplay: {
                delay: 4000,
                reverseDirection: true,
            },
            navigation: {
                nextEl: '.hm-brands--button__SWIPER2_NEXT',
                prevEl: '.hm-brands--button__SWIPER2_PREV',
            },
            breakpoints: {
                200: {
                    slidesPerView: "auto",
                    spaceBetween: 12,
                },
                1200: {
                    slidesPerView: "auto",
                    spaceBetween: 16,
                },
                1300: {
                    slidesPerView: "auto",
                    spaceBetween: 24,
                },
                5000: {
                    slidesPerView: "auto",
                    spaceBetween: 24,
                }
            },
            on: {
                init() {
                    this.el.addEventListener('mouseenter', () => {
                        this.autoplay.stop();
                    });

                    this.el.addEventListener('mouseleave', () => {
                        this.autoplay.start();
                    });
                }
            },
        });
    }














    /* ---------- ////////// ********** СТРАНИЦА БОНУСЫ ********** ////////// ---------- */


    /* ---------- ********** СЕКЦИЯ FAQ ********** ---------- */


    if (document.querySelector(".bn-faq")) {

        const bnFaqHead = Array.from(document.querySelectorAll(".bn-faq--div__HEAD"));
        const bnfaqSvg = Array.from(document.querySelectorAll(".bn-faq--svg__HEAD"));
        const bnFaqBody = Array.from(document.querySelectorAll(".bn-faq--p__BODY"));


        bnFaqHead.forEach((v, i, a) => {
            a[i].addEventListener("click", () => {
                a[i].classList.toggle("__bn-faq--div__HEAD");
                bnfaqSvg[i].classList.toggle("__bn-faq--svg__HEAD");
                bnFaqBody[i].classList.toggle("__bn-faq--p__BODY");
            });
        });

    }

});