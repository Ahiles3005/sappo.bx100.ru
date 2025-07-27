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