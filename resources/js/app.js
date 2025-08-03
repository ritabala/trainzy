import './bootstrap';
import './bootstrap';
import 'flowbite';
import ApexCharts from 'apexcharts';
import swal from 'sweetalert2';
import './gym-listing';
window.Swal = swal;
window.ApexCharts = ApexCharts;


document.addEventListener("livewire:navigated", () => {

});

let attrs = [
    'snapshot',
    'effects',
    // 'click',
    // 'id'
];

function snapKill() {
    document.querySelectorAll('div, nav, a, header').forEach(function(element) {
        for (let i in attrs) {
            if (element.getAttribute(`wire:${attrs[i]}`) !== null) {
                element.removeAttribute(`wire:${attrs[i]}`);
            }
        }
    });
}

window.addEventListener('load',(ev) =>{
       snapKill();
});
