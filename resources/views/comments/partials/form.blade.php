<div class="mb-2 mt-2">
    @auth
        <form action="#" method="POST">
            @csrf
    
            <div class="form-group">
                <textarea class="form-control" id="content" name="content" ></textarea>
            </div>
    
            <div><input type="submit" value="Add Comment!" class="btn btn-primary btn-block"></div>
        </form>
    @else
        <a href="{{ route('login') }}">Sign in</a> to post comments!
    @endauth
</div>
<hr>