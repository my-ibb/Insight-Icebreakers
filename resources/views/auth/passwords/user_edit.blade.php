@extends('layouts.app') <!-- レイアウトを継承 -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit User</div>

                <div class="card-body">
                    <!-- エラー表示 -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.update', $user->id) }}">
                        @csrf <!-- CSRFトークン -->
                        @method('PUT') <!-- HTMLフォームはPUTやDELETEメソッドをサポートしていないので、このディレクティブを使用 -->

                        <div class="form-group">
                            <label for="username">User Name</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">User Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>

                        <div class="form-group">
                            <label for="role">User Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <!-- 必要に応じて他の役割を追加 -->
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
