<div class="progress progress-striped active" style="height:30px !important;line-height:30px !important;">
    <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="{{$progress}}" aria-valuemin="0" aria-valuemax="100" style="width:{{$progress}}%;font-size:18px !important; line-height:18px !important;">
            <span><font class="progress-number">{{$progress}}</font>%
            <font class="progress-status hide">
                    @if (isset($text) && $text)
                        ({{@$text}})
                    @endif
                </font></span>
    </div>
</div>

