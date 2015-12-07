<div class="row">

    <div class="col-sm-8 col-sm-offset-1">
        By accepting our Terms you agree that you have read, understand,<br/> and accept the disclosure described above and our <a href="/privacypolicy" onclick="LoadStep('/privacypolicy'); return false;">Privacy Policy</a>
    </div>

    <div class="col-sm-2">
        @if (Session::get('accepted_disclosure'))
            <a id="reviewdiscbtn" href="javascript:void(0);"  class="btn btn-warning"  name="unaccept" onclick="jQuery('#disclosure_display_box').removeClass('hide'); jQuery('#reviewdiscbtn').addClass('hide');jQuery('#disclosure_xhidden_accept_btn_secret').removeClass('hide'); return false; ">
                Review Disclosure</a>
            <input type="submit" class="btn btn-success hide" value="Accept Terms" name="accept" id="disclosure_xhidden_accept_btn_secret">

        @else
            <input type="submit" class="btn btn-success" value="Accept Terms" name="accept">
        @endif

        <input type="hidden" name="accept" value="1">
    </div>


</div>