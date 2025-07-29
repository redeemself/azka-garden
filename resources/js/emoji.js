document.addEventListener('DOMContentLoaded', () => {
  const textarea = document.getElementById('message');
  const emojiPicker = document.getElementById('emoji-picker');

  if (!textarea || !emojiPicker) return;

  emojiPicker.querySelectorAll('.emoji-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const emoji = btn.getAttribute('data-emoji');
      insertAtCursor(textarea, emoji);
      textarea.focus();
    });
  });

  function insertAtCursor(input, text) {
    const start = input.selectionStart;
    const end = input.selectionEnd;
    const value = input.value;
    input.value = value.substring(0, start) + text + value.substring(end);
    input.selectionStart = input.selectionEnd = start + text.length;
  }
});
