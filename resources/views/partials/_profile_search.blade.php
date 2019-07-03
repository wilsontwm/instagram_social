{!! Form::model(null, ['url' => route('search'), 'method' => 'POST']) !!}
<div class="margin">
    <input name="insta_account" id="user-search" class="form-control search-bar" type="text" placeholder="Type an 'insta_account' and Enter" autocomplete="off">
</div>
{!! Form::close() !!}

<script>
    $(document).ready(function(){
        var users = {
            url: '/profile/autocomplete',
            getValue: 'username',
            requestDelay: 500,
            list: {
                match: {
                    enabled: true
                },
                maxNumberOfElements: 10
            },
            template: {
                type: "iconLeft",
                fields: {
                    iconSrc: "icon"
                }
            }
        };

        $("#user-search").easyAutocomplete(users);

        // reply the comment on Enter pressed
        $(this).keyup = function(e) {
            if(e.keyCode === 13){
                $('#user-search').closest('form').submit();
            }
            return true;
        }
    });
</script>