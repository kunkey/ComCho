(() => {
    const formTuvi = `
    <div class="cps-wrap" id="cps-wrap">
        <div class="cps-title">
            Tìm kiếm mã giảm giá Shopee
        </div>
        <div id="cps-search-form">
            <input type="text" name="cps_search_url" id="cps-search-url" class="form-control" value="" placeholder="Nhập link sản phẩm để tìm kiếm voucher" required="" autocomplete="on" onClick="this.setSelectionRange(0, this.value.length);">
            <button class="cps-search-btn" id="cps-btn-search-voucher">Tìm kiếm</button>
        </div>
        <div id="cps-error-message"></div>
        <div id="cps-loading-title"></div>
        <div id="cps-loading-bar">
            <div id="cps-progress-bar" class="cps-progress-bar cps-progress-bar-striped" style="width: 0%;">0%</div>
        </div>
        <div id="cps-vouchers"></div>
        <div class="cps-overlay">
        </div>
        <div class="cps-con-loading">
            <div class="cps_loading__letter">Đ</div>
            <div class="cps_loading__letter">a</div>
            <div class="cps_loading__letter">n</div>
            <div class="cps_loading__letter">g</div>
            <div class="cps_loading__letter">&nbsp;</div>
            <div class="cps_loading__letter">T</div>
            <div class="cps_loading__letter">ả</div>
            <div class="cps_loading__letter">i</div>
            <div class="cps_loading__letter">.</div>
            <div class="cps_loading__letter">.</div>
            <div class="cps_loading__letter">.</div>
        </div>
    </div>`;

    const meta = document.createElement("meta");
    meta.setAttribute("name", "viewport");
    meta.setAttribute("content", "width=device-width, initial-scale=1, maximum-scale=1");

    document.getElementById('tuvi').innerHTML = formTuvi;

})();