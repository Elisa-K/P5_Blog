$('.error').hide();

tinymce.init({
	selector: '#content-tiny',
	min_height: 600,
	language: 'fr_FR',
	language_url: '/vendor/tweeb/tinymce-i18n/langs/fr_FR.js',
	plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons', 'codesample'],
	toolbar: 'undo redo | blocks | ' + 'bold italic underline strikethrough | ' + 'forecolor backcolor emoticons | alignleft aligncenter ' + 'alignright alignjustify | bullist numlist outdent indent | ' + 'insertfile image media link codesample | ' + 'removeformat',
	content_style: 'body { font-family:Monterrat,Arial,sans-serif; font-size:16px }',
	protect: [
		/{%(.*)%}/g, // Allow TWIG control codes
		/{{(.*)}}/g, // Allow TWIG output codes
		/{#(.*)#}/g, // Allow TWIG comment codes
	],
	automatic_uploads: true,
	file_picker_types: 'image',
	file_picker_callback: (cb, value, meta) => {
		const input = document.createElement('input');
		input.setAttribute('type', 'file');
		input.setAttribute('accept', 'image/*');
		input.addEventListener('change', (e) => {
			const file = e.target.files[0];
			const reader = new FileReader();
			reader.addEventListener('load', () => {
				const id = 'blobid' + (new Date()).getTime();
				const blobCache =  tinymce.activeEditor.editorUpload.blobCache;
				const base64 = reader.result.split(',')[1];
				const blobInfo = blobCache.create(id, file, base64);
				blobCache.add(blobInfo);
				cb(blobInfo.blobUri(), { title: file.name });
			});
			reader.readAsDataURL(file);
		});
		input.click();
	},
	setup: function (editor) {
		editor.on('change', function () {
			tinymce.triggerSave();
			chkSubmit();
		});
	}
});

$(document).on('click', '#submit', chkSubmit);

function chkSubmit() {
	var msg = tinymce.get("content-tiny").getContent();
	console.log(msg);
	var textmsg = $.trim($(msg).text());

		if (textmsg == '') {
			$('.error').show();
			$('.error').html('Veuillez écrire le contenu de votre article avant de le publier.');
			return false;
		}else{
			$('.error').hide();
			$('.error').html('');
		}
}