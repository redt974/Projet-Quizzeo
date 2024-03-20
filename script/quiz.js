const selectImage = document.querySelector('.select-image');
const inputFile = document.querySelector('#file');
const imgArea = document.querySelector('.img-area');

selectImage.addEventListener('click', function () {
	inputFile.click();
})

inputFile.addEventListener('change', function () {
	const image = this.files[0]
	if(image.size < 2000000) {
		const reader = new FileReader();
		reader.onload = ()=> {
			const allImg = imgArea.querySelectorAll('img');
			allImg.forEach(item=> item.remove());
			const imgUrl = reader.result;
			const img = document.createElement('img');
			img.src = imgUrl;
			imgArea.appendChild(img);
			imgArea.classList.add('active');
			imgArea.dataset.img = image.name;
		}
		reader.readAsDataURL(image);
	} else {
		alert("Image size more than 2MB");
	}
})

// Fonction pour ajouter une nouvelle question
function addQuestion() {
	var container = document.getElementById('questionsContainer');
	var questionIndex = container.children.length + 1; // Index de la question

	var questionForm = document.createElement('div');
	questionForm.classList.add('form');
	questionForm.innerHTML = '<label for="questions">Nom de la Question :</label>' +
		'<input type="text" name="questions[]" required>' +
		'<label for="points">Points :</label>' +
		'<input type="number" name="points" required>' +
		'<br>' +
		'<div class="answersContainer"></div>' +
		'<button type="button" onclick="addAnswer(this)">Ajouter une Réponse</button>' +
		'<button type="button" onclick="removeQuestion(this)">Supprimer la Question</button>' +
		'<br>';

	container.appendChild(questionForm);
}

// Fonction pour ajouter une nouvelle réponse à une question
function addAnswer(button) {
	var answersContainer = button.previousSibling; // Sélectionner le conteneur de réponses de la question
	var answerForms = answersContainer.getElementsByClassName('answerForm'); // Sélectionner les champs de réponse spécifiques à la question

	// Vérifier si le nombre de réponses est inférieur à 4 avant d'ajouter une nouvelle réponse
	if (answerForms.length < 4) {
		var questionIndex = answersContainer.parentElement.querySelector('input').value; // Index de la question
		var answerForm = document.createElement('div');
		answerForm.classList.add('form');
		answerForm.classList.add('answerForm'); // Ajouter la classe spécifique
		answerForm.innerHTML = '<label for="answers">Réponse :</label>' +
			'<input type="text" name="answers[' + questionIndex + '][]" required>' +
			'<label for="correct">Correct :</label>' +
			'<select name="correct[' + questionIndex + '][]">' +
			'<option value="1">Oui</option>' +
			'<option value="0">Non</option>' +
			'</select>' +
			'<button type="button" onclick="removeAnswer(this)">Supprimer la Réponse</button>' +
			'<br>';

		answersContainer.appendChild(answerForm);

		// Mettre à jour l'index de la question
		updateQuestionIndex(answersContainer.parentElement, questionIndex);

		// Masquer le bouton d'ajout si la limite est atteinte
		if (answerForms.length === 4) {
			button.style.display = 'none'; // Masquer le bouton
		}
	}
}


// Fonction pour mettre à jour l'index de la question
function updateQuestionIndex(questionContainer, newIndex) {
	var questionInput = questionContainer.querySelector('input[name="points"]');
	questionInput.value = newIndex;
}

// Fonction pour ajouter une nouvelle question avec une réponse libre
function addQuestionWithFreeResponse() {
	var container = document.getElementById('questionsContainer');
	var questionIndex = container.children.length + 1; // Index de la question

	var questionForm = document.createElement('div');
	questionForm.classList.add('form');
	questionForm.innerHTML = '<label for="free-question">Nom de la Question :</label>' +
		'<input type="text" name="free-questions[]" required>' +
		'<br>' +
		'<button type="button" onclick="removeQuestion(this)">Supprimer la Question</button>' +
		'<br>';
	container.appendChild(questionForm);
}


// Fonction pour supprimer une question
function removeQuestion(button) {
	var questionForm = button.parentElement;
	questionForm.parentElement.removeChild(questionForm);
}

// Fonction pour supprimer une réponse
function removeAnswer(button) {
	var answerForm = button.parentElement;
	answerForm.parentElement.removeChild(answerForm);
}