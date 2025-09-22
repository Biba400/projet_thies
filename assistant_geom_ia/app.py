from flask import Flask, request, jsonify, send_file
from flask_cors import CORS
import os
from openai import OpenAI
from dotenv import load_dotenv
from gtts import gTTS
import uuid

load_dotenv()
api_key = os.getenv("OPENAI_API_KEY")

app = Flask(__name__)
CORS(app)

client = OpenAI(api_key=api_key)

@app.route('/ask', methods=['POST'])
def ask():
    data = request.get_json()
    question = data.get('question', '')

    try:
        completion = client.chat.completions.create(
            model="gpt-3.5-turbo",
            messages=[
                {"role": "system", "content": "Tu es un assistant expert en géomatique, topographie, foncier et géodésie au Sénégal."},
                {"role": "user", "content": question}
            ],
            temperature=0.5
        )

        answer = completion.choices[0].message.content.strip()

        # Créer audio avec gTTS
        tts = gTTS(answer, lang='fr')
        filename = f"static/response_{uuid.uuid4()}.mp3"
        tts.save(filename)

        return jsonify({'answer': answer, 'audio_url': filename})

    except Exception as e:
        return jsonify({'answer': "Erreur : " + str(e)})

if __name__ == '__main__':
    app.run(debug=True)
