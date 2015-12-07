{{ Form::open(array('url' => URL::to('/disclosuref'), 'class' => 'form-horizontal')) }}


    <div class="row" id="disclosure_display_box">
        <div class="col-sm-8 col-sm-offset-2">

            <div id="disclosure_text" class="well well-sm text-info">
                {{ get_disclosure_text(true) }}

                <div class="text-center hide">
                    By accepting our Terms you agree that you have read, understand, and accept <br/> the disclosure described above and our <a href="/privacypolicy" onclick="LoadStep('/privacypolicy'); return false;">Privacy Policy</a> <br/>
                </div>

                @include('disclosureagreebtn')

                <div class="gottaagreetho hide"><div class="alert alert-danger">You must agree to these terms to continue.</div></div>


            </div>
        </div>
    </div>
<?php
$encrypted = Crypt::encrypt(URL::secure(URL::full()));
?>

<input type="hidden" name="rd" value="{{ $encrypted }}">
</form>



@if (Session::get('accepted_disclosure'))
<script>
    jQuery('#disclosure_display_box').addClass('hide');
</script>
@endif