window.onload = function (){
    document.getElementsByClassName('navbar-toggler')[0].onclick = function(){
        document.getElementById('navbarSupportedContent').classList.toggle('collapse');
        //document.getElementsByClassName('navbar-nav')[0].classList.toggle('collapse');
        //document.getElementsByClassName('navbar-nav')[0].classList.toggle('show-menu');
    }
    if(document.getElementById('navbarDropdown')) {
        document.getElementById('navbarDropdown').onclick = function () {
            document.getElementById('navbarDropdown').nextElementSibling.classList.toggle('block');
        }
    }
};

function deletePost(url){
    if(confirm("Are you sure?")){
        window.location.href = url;
    }
    return false;
}
