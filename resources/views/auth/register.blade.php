<x-guest-layout>
    <livewire:auth.register-gym />
</x-guest-layout>

<script>
function previewLogo(input) {
    const preview = document.getElementById('logo-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="h-24 w-24 rounded-lg object-cover">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
