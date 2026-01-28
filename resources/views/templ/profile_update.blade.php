@extends('templ.head')

@section('tmplt-contnt')

<main id="main">

    {{-- Breadcrumbs --}}
    <section class="breadcrumbs bg-color shadow-lg">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Edit Profile</h2>
            </div>
        </div>
    </section>

    {{-- Flash Message --}}
    @if(session('info'))
        <div class="container mt-3">
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        </div>
    @endif

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf

                    {{-- Basic Info --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header fw-bold">
                            Basic Information
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name"
                                       value="{{ old('name', $user->name) }}"
                                       class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username"
                                       value="{{ old('username', $user->username) }}"
                                       class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>National ID</label>
                                <input type="text" name="national_id"
                                       value="{{ old('national_id', $user->national_id) }}"
                                       class="form-control" required>
                            </div>

                        </div>
                    </div>

                    {{-- Affiliation --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header fw-bold">
                            Affiliation
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input affiliation-radio"
                                           type="radio"
                                           name="affiliation"
                                           id="aff_university"
                                           value="university"
                                           {{ $user->uni_id ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aff_university">
                                        University
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input affiliation-radio"
                                           type="radio"
                                           name="affiliation"
                                           id="aff_others"
                                           value="others"
                                           {{ $user->institution_name ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aff_others">
                                        Others
                                    </label>
                                </div>
                            </div>

                            {{-- University Section --}}
                            <div id="universitySection"
                                 style="{{ $user->uni_id ? '' : 'display:none' }}">

                                <div class="mb-3">
                                    <label>University</label>
                                    <select name="uni_id"
                                            id="uniSelect"
                                            class="form-control"
                                            {{ $user->uni_id ? '' : 'disabled' }}>
                                        <option value="">Select University</option>
                                        @foreach($unis as $uni)
                                            <option value="{{ $uni->id }}"
                                                {{ $user->uni_id == $uni->id ? 'selected' : '' }}>
                                                {{ $uni->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Faculty</label>
                                    <select name="fac_id"
                                            id="facSelect"
                                            class="form-control"
                                            {{ $user->fac_id ? '' : 'disabled' }}>
                                        <option value="">Select Faculty</option>
                                        @foreach($faculties as $faculty)
                                            <option value="{{ $faculty->id }}"
                                                {{ $user->fac_id == $faculty->id ? 'selected' : '' }}>
                                                {{ $faculty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Institution Section --}}
                            <div id="institutionSection"
                                 style="{{ $user->institution_name ? '' : 'display:none' }}">

                                <div class="mb-3">
                                    <label>Institution Name</label>
                                    <input type="text"
                                           name="institution_name"
                                           class="form-control"
                                           value="{{ old('institution_name', $user->institution_name) }}"
                                           {{ $user->institution_name ? '' : 'disabled' }}>
                                </div>

                            </div>

                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-success px-5">
                            Update Profile
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

</main>

{{-- JS --}}
<script>
    document.querySelectorAll('.affiliation-radio').forEach(radio => {
        radio.addEventListener('change', function () {

            const uniSection = document.getElementById('universitySection');
            const instSection = document.getElementById('institutionSection');

            const uniSelect = document.getElementById('uniSelect');
            const facSelect = document.getElementById('facSelect');

            if (this.value === 'university') {
                uniSection.style.display = 'block';
                instSection.style.display = 'none';

                uniSelect.disabled = false;
                facSelect.disabled = false;
                document.querySelector('[name="institution_name"]').disabled = true;
            } else {
                uniSection.style.display = 'none';
                instSection.style.display = 'block';

                uniSelect.disabled = true;
                facSelect.disabled = true;
                document.querySelector('[name="institution_name"]').disabled = false;
            }
        });
    });
</script>

@endsection
