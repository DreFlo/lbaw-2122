@extends('layouts.app')

@section('page-title', 'Edit Profile | ')

@section('content')
    <div>
        <h1 class="h1 text-center">Edit Profile</h1>
    </div>

    <div class="mt-4 d-flex">
        <div class="col-3 px-2 d-none d-md-block">
            <a href="#" style="font-size: 1.3rem">
                <span>Change Password</span>
            </a>
            <a href="#" style="font-size: 1.3rem">
                <span>Delete Account</span>
            </a>
        </div>
        <div class="col-12 col-md-6">
            <div class="container edit-form p-3">
                <form method="post" action="/profile/edit" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {!! method_field('PATCH') !!}

                    <div class="form-floating mb-3">
                        <input name="name" type="text" class="form-control" id="name" value="{{ $user->name }}">
                        <label for="name">Name</label>
                    </div>
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="email" name="email" class="form-control" id="email" placeholder="Your Email Address" value="{{ $user->email }}">
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input value="{{ $user->birthdate }}" name="birthdate" type="date" class="form-control" id="birthdate">
                                <label for="birthdate">Birthdate</label>
                            </div>
                        </div>
                    </div>
                    <div class="my-3 mx-auto d-flex">
                        <div id="profile-picture-box" class="col-md-4 align-self-center m-auto">
                            <div id="image-input" class="image-input mb-3">
                                <input name="image" type="file" class="form-control-file" accept="image/*" id="profile-picture">
                            </div>
                        </div>
                        <script>
                            document.getElementById('profile-picture').addEventListener('input', (e) => {
                                let file = e.target.files[0];
                                let reader = new FileReader();
                                reader.onloadend = function () {
                                    document.getElementById('image-input').style.backgroundImage = "url('" + reader.result + "')"
                                }
                                if (file) {
                                    reader.readAsDataURL(file);
                                }
                            })
                        </script>
                    </div>
                    <div class="row px-3">
                        <input type="submit" value="Confirm">
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
