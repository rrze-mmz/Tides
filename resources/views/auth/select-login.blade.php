@php use Slides\Saml2\Repositories\TenantRepository; @endphp
<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            Please select a login provider
        </div>

        <div class=" flex  justify-center">

            <a href="{{route('saml.login', $tenant_uuid )}}">
                <div class="sm:max-w-md mt-6 mx-4 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    WebSSO
                </div>
            </a>


            <a href="{{route('login')}}">
                <div class=" sm:max-w-md mt-6 mx-4 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    Local login
                </div>
            </a>
        </div>
    </div>


</x-guest-layout>
