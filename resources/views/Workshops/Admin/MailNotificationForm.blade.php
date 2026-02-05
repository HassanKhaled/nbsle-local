@extends('loggedTemp.head')

@section('loggedContent')

<style>
  .email-composer-container {
    max-width: 1000px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.12);
  }

  h2 { font-size:26px; color:#2c3e50; font-weight:600; margin-bottom: 20px; }
  label { font-weight:600; margin-bottom:6px; display:block; color:#2c3e50; }

  select, input[type=text] {
    width:100%;
    padding:10px 12px;
    margin-bottom:16px;
    border-radius:10px;
    border:1px solid #000000;
  }

  .toolbar {
    display:flex;
    gap:4px;
    flex-wrap:wrap;
    margin-bottom:10px;
  }

  .toolbar button,
  .toolbar select {
    background:#f1f3f7;
    border:none;
    height:34px;
    border-radius:8px;
    cursor:pointer;
    padding:8px 10px;
  }

  .toolbar button:hover,
  .toolbar select:hover {
    background:#e1e4e8;
  }

  .editor {
    min-height:240px;
    border:1px solid #000000;
    border-radius:12px;
    padding:14px;
    margin-top:10px;
  }

  .editor:empty::before {
    content:'Write your message...';
    color:#999;
  }

  /* Inline link style */
  .editor a {
    background:#eef3ff;
    padding:2px 6px;
    border-radius:6px;
    color:#2c5cff;
    text-decoration:none;
  }

  .attachments-preview {
    margin-top:10px;
    display:flex;
    gap:8px;
    flex-wrap:wrap;
  }

  .attachment-chip {
    background:#eef1f7;
    padding:6px 10px;
    border-radius:10px;
    font-size:13px;
  }

  .send {
    margin-top:20px;
    width:100%;
    background:#4a7cf7;
    color:white;
    border:none;
    padding:14px;
    border-radius:12px;
    font-size:16px;
    font-weight:600;
  }

  .field-error {
  color: #c0392b;
  font-size: 13px;
  margin-top: 4px;
}

</style>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="container-fluid py-5 mt-5">
  <div class="email-composer-container">

    <a href="{{ route('admin.workshops.index') }}" class="back-btn">← Back to Workshops</a>

    <form method="POST"
          action="{{ route('send-MailNotificationForMembers', $workshop->id) }}"
          enctype="multipart/form-data"
          id="emailForm">
      @csrf

      <h2>📧 Email Composer</h2>

      <div class="workshop-info">
        <p><strong>Workshop:</strong> {{ $workshop->workshop_ar_title }}</p>
        <p><strong>ID:</strong> #{{ $workshop->id }}</p>
      </div>

      <label>Subject *</label>
      <input type="text" name="title" required>

      <label>Message *</label>

      <div class="toolbar">
        <button type="button" onclick="cmd('bold')"><b>B</b></button>
        <button type="button" onclick="cmd('italic')"><i>I</i></button>
        <button type="button" onclick="cmd('underline')"><u>U</u></button>

        <select onchange="setFontSize(this.value)" style="width:10%">
          <option value="">font-size</option>
          <option value="3">16px</option>
          <option value="4">18px</option>
          <option value="5">24px</option>
          <option value="6">32px</option>
        </select>
      </div>

      <!-- Attachments -->
      <input type="file" name="attachments[]" multiple id="attachmentsInput" >
      <div class="attachments-preview" id="attachmentsPreview"></div>

      <textarea name="body" id="bodyTextarea" hidden></textarea>
      <div class="editor" id="editor" contenteditable="true" style="color: #000000;"></div>

      <button type="submit" class="send">Send Email</button>
    </form>
  </div>
</div>

<script>
  const form = document.getElementById('emailForm');
  const editor = document.getElementById('editor');
  const textarea = document.getElementById('bodyTextarea');
  const subjectInput = document.querySelector('input[name="title"]');
  const sendBtn = form.querySelector('.send');
  const attachmentsInput = document.getElementById('attachmentsInput');

  // Disable send on load
  sendBtn.disabled = true;

  /* ---------- Helpers ---------- */

  function editorHasText() {
    return editor.innerText.trim().length > 0;
  }

  function showError(el, msg) {
    removeError(el);
    const div = document.createElement('div');
    div.className = 'field-error';
    div.innerText = msg;
    el.parentNode.insertBefore(div, el.nextSibling);
  }

  function removeError(el) {
    const next = el.nextSibling;
    if (next && next.classList && next.classList.contains('field-error')) {
      next.remove();
    }
  }

  function validateForm() {
    let valid = true;

    // Subject validation
    if (!subjectInput.value.trim()) {
      showError(subjectInput, 'Subject is required');
      valid = false;
    } else {
      removeError(subjectInput);
    }

    // Message validation
    if (!editorHasText()) {
      showError(editor, 'Message body is required');
      valid = false;
    } else {
      removeError(editor);
    }

    sendBtn.disabled = !valid;
    return valid;
  }

  /* ---------- Editor Commands ---------- */

  function cmd(command) {
    document.execCommand(command, false, null);
    editor.focus();
  }

  function setFontSize(size) {
    if (!size) return;
    document.execCommand('fontSize', false, size);
    editor.focus();
  }

  /* ---------- Auto-link ---------- */

function linkifyText() {
  // Only wrap plain text URLs, ignore existing <a>
  const walker = document.createTreeWalker(editor, NodeFilter.SHOW_TEXT, null, false);

  const urls = [];
  while (walker.nextNode()) {
    const node = walker.currentNode;
    const urlRegex = /(https?:\/\/[^\s]+|www\.[^\s]+)/gi;

    if (urlRegex.test(node.nodeValue)) {
      urls.push(node);
    }
  }

  urls.forEach(textNode => {
    const html = textNode.nodeValue.replace(
      /(https?:\/\/[^\s]+|www\.[^\s]+)/gi,
      (match) => {
        let href = match.startsWith('http') ? match : 'https://' + match;
        return `<a href="${href}" target="_blank">${match}</a>`;
      }
    );

    const span = document.createElement('span');
    span.innerHTML = html;
    textNode.parentNode.replaceChild(span, textNode);
  });
}


  editor.addEventListener('keyup', e => {
    if (e.key === ' ' || e.key === 'Enter') {
      linkifyText();
    }
    validateForm();
  });

  editor.addEventListener('paste', () => {
    setTimeout(() => {
      linkifyText();
      validateForm();
    }, 50);
  });

  editor.addEventListener('blur', validateForm);
  subjectInput.addEventListener('input', validateForm);
  subjectInput.addEventListener('blur', validateForm);

  /* ---------- Attachments Preview ---------- */

  attachmentsInput.addEventListener('change', e => {
    const preview = document.getElementById('attachmentsPreview');
    preview.innerHTML = '';
    [...e.target.files].forEach(file => {
      preview.innerHTML += `<div class="attachment-chip">📎 ${file.name}</div>`;
    });
  });

  /* ---------- Submit ---------- */

  form.addEventListener('submit', function (e) {
    if (!validateForm()) {
      e.preventDefault();
      editor.focus();
      return false;
    }

    textarea.value = editor.innerHTML;
    sendBtn.disabled = true;
    sendBtn.innerText = 'Sending...';
  });
</script>



@endsection
