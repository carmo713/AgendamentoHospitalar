<!-- filepath: /home/carmo/Documentos/agendamento/agendamento/resources/views/auth/register.blade.php -->
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" class="block mt-1 w-full" required>
                <option value="medico">Médico</option>
                <option value="paciente">Paciente</option>
            </select>
        </div>

        <!-- Especialidade -->
        <div class="mt-4" id="especialidade-field" style="display: none;">
            <x-input-label for="especialidade" :value="__('Especialidade')" />
            <x-text-input id="especialidade" class="block mt-1 w-full" type="text" name="especialidade" />
            <x-input-error :messages="$errors->get('especialidade')" class="mt-2" />
        </div>

        <!-- CRM -->
        <div class="mt-4" id="crm-field" style="display: none;">
            <x-input-label for="crm" :value="__('CRM')" />
            <x-text-input id="crm" class="block mt-1 w-full" type="text" name="crm" />
            <x-input-error :messages="$errors->get('crm')" class="mt-2" />
        </div>

        <!-- CPF -->
        <div class="mt-4" id="cpf-field" style="display: none;">
            <x-input-label for="cpf" :value="__('CPF')" />
            <x-text-input id="cpf" class="block mt-1 w-full" type="text" name="cpf" />
            <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
        </div>

        <!-- Telefone -->
        <div class="mt-4" id="telefone-field" style="display: none;">
            <x-input-label for="telefone" :value="__('Telefone')" />
            <x-text-input id="telefone" class="block mt-1 w-full" type="text" name="telefone" />
            <x-input-error :messages="$errors->get('telefone')" class="mt-2" />
        </div>

        <!-- Data de Nascimento -->
        <div class="mt-4" id="data_nascimento-field" style="display: none;">
            <x-input-label for="data_nascimento" :value="__('Data de Nascimento')" />
            <x-text-input id="data_nascimento" class="block mt-1 w-full" type="date" name="data_nascimento" />
            <x-input-error :messages="$errors->get('data_nascimento')" class="mt-2" />
        </div>

        <!-- Endereço -->
        <div class="mt-4" id="endereco-field" style="display: none;">
            <x-input-label for="endereco" :value="__('Endereço')" />
            <x-text-input id="endereco" class="block mt-1 w-full" type="text" name="endereco" />
            <x-input-error :messages="$errors->get('endereco')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.getElementById('role').addEventListener('change', function () {
            var role = this.value;
            document.getElementById('especialidade-field').style.display = role === 'medico' ? 'block' : 'none';
            document.getElementById('crm-field').style.display = role === 'medico' ? 'block' : 'none';
            document.getElementById('cpf-field').style.display = role === 'paciente' ? 'block' : 'none';
            document.getElementById('telefone-field').style.display = role === 'paciente' ? 'block' : 'none';
            document.getElementById('data_nascimento-field').style.display = role === 'paciente' ? 'block' : 'none';
            document.getElementById('endereco-field').style.display = role === 'paciente' ? 'block' : 'none';
        });
    </script>
</x-guest-layout>