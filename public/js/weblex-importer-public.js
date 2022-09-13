var $propalQuizz = document.querySelector('.propal-quizz');

var $rna = document.querySelector('#rna');
var $reponse = document.querySelector('#reponse');

if ($rna && $response) {
    $rna.style.setProperty('display', 'none');
    $reponse.style.setProperty('display', 'none');

    $propalQuizz.addEventListener('click', function (event) {
        $rna.style.setProperty('display', 'none');
        $reponse.style.setProperty('display', 'none');

        if ('#reponse' === event.target.hash && event.target.parentNode.classList.contains('quizz-a')) {
            return $reponse.style.setProperty('display', 'block');
        }

        if ('#reponse' === event.target.hash && event.target.parentNode.classList.contains('quizz-b')) {
            return $reponse.style.setProperty('display', 'block');
            // return $rna.style.setProperty('display', 'block');
        }
    }, true);
}