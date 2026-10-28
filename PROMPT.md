Build Slot Fill Desk in THIS folder: C:\xampp\htdocs\slot-fill-desk

A recruiter-ready demo that extracts booking slots (date, time window, city) from a Harbor Desk free-text request.

Laravel 13 + Vue 3 starter kit is already here if present. Reuse it. Do not create a second app inside NewPortfolio.

## Must satisfy

- Models: `SlotJob` (source_text, status), `SlotFill` (slot_job_id, date nullable, window nullable, city nullable, missing json).
- Deterministic regex/keyword extractors first (ISO dates, morning/afternoon/evening, city names from a small allow-list). Optional LLM only for leftover missing slots. Timeout 12s. Never 500.
- `App\Services\Llm\LlmClient` with `FakeLlmClient` in tests and when no `GEMINI_API_KEY`. Optional Gemini when key is set.
- Seed 3 requests. UI: paste text, show slots, missing chips.
- Pest: "pickup tomorrow afternoon in Miami" fills window afternoon and city Miami; FakeLlm bound; guest POST 200.
- README mermaid: Text → regex slots → optional LLM for missing. How to run without API key.

## GitHub

- git config user.name "Oscar Munoz" / user.email "oscarxxi@gmail.com"
- Commit: feat: add slot fill desk
- Public https://github.com/oscarmmi/slot-fill-desk
- gh repo edit --description "Extract booking date, time window, and city from Harbor Desk free text (Laravel + Vue)." --add-topic laravel --add-topic vue --add-topic ai --add-topic php --add-topic nlp
- Stop. Print the URL.

No Impeccable. No Redis. No real addresses.
