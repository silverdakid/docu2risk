from flask import Flask, render_template, jsonify, request
from dotenv import load_dotenv
from OCR import *
from OpenAIAPI import *
from traitementJSON import *
from scraping import *
from answersToPoints import *
import os
############################################################################
load_dotenv()
app = Flask(__name__)
app.config['UPLOAD_FOLDER'] = 'uploads'

@app.route("/")
def hello():
    return "Hello World!"


@app.route('/get_answers_docs', methods=['POST'])
def get_answers() :
    base_path = get_files_path()
    try:
        # On récupère la data d'un JSON comme ceci:
        data = request.get_json()
        user_id = data.get('user_id')
        wolfsberg_path = os.path.join(base_path, 'wolfsberg' + user_id + '.pdf')
        ici_path = os.path.join(base_path, 'ici' + user_id + '.pdf')
        invoice_path = os.path.join(base_path, 'invoice' + user_id + '.pdf')
        # Ici aussi :
        counterparty_name = data.get('name')
        answers = {"Name" : counterparty_name}


        if os.path.exists(wolfsberg_path) == False or counterparty_name is None or os.path.exists(ici_path) == False or user_id is None:
            return jsonify({'error': 'File "wolfsberg.pdf", "ICI.pdf", counterparty name or user_id not specified'}), 400
        
        else :        
            client = init_client(os.getenv("OPENAI_API_KEY"))
            answers_Wolfsberg = get_blank_questions_json("utils/questionsAI.json", "Wolfsberg")
            answers_ICI = get_blank_questions_json("utils/questionsAI.json", "ICI")
            make_pdf_readable(wolfsberg_path , wolfsberg_path)
            wolfsberg = read_pdf(wolfsberg_path)
            make_pdf_readable(ici_path , ici_path)
            ici = read_pdf(ici_path)
            for i in range (len(wolfsberg)):
                print("Analyse du questionnaire Wolfsberg : Page " + str(i + 1) + "/" + str(len(wolfsberg)))
                answerjson = (wolfsberg_questions(client, wolfsberg[i].get_text(), get_questions_json_string("utils/wolfsberg_AI.json", "FCCQ_CBDDQ")))
                print(answerjson)
                responsejson = convert_wolfsberg_2_standard(answerjson, "FCCQ_CBDDQ")
                print(responsejson)
                for key in responsejson : 
                    if responsejson[key] != "N/A"  :
                        if key in answers_Wolfsberg.keys() and answers_Wolfsberg[key] == "N/A" :
                            answers_Wolfsberg[key] = responsejson[key]
                            print(key, answers_Wolfsberg[key])
                if "N/A" not in answers_Wolfsberg.values() :
                    break
            for i in range (1, len(ici)):
                print("Analyse du questionnaire ICI : Page " + str(i + 1) + "/" + str(len(ici)))
                responsejson = (ICI_questions(client, ici[i].get_text(), get_questions_json_string("utils/questionsAI.json", "ICI")))
                for key in responsejson : 
                    if responsejson[key] != "N/A"  :
                        if key in answers_ICI.keys() and answers_ICI[key] == "N/A" :
                            answers_ICI[key] = responsejson[key]
                            print(key, answers_ICI[key])
                if "N/A" not in answers_ICI.values() :
                    break
            answers.update(convert_dict_for_app(answers_Wolfsberg,"Wolfsberg", True))
            answers.update(convert_dict_for_app(answers_ICI,"ICI", True))
            #ici.close()
            #wolfsberg.close()

            if os.path.exists(invoice_path) == True :
                answers_invoice = get_blank_questions_json("utils/questionsAI.json", "Invoice")
                make_pdf_readable(invoice_path , invoice_path)
                invoice = read_pdf(invoice_path)
                for i in range (len(invoice)):
                    print("Analyse de la facture : Page " + str(i + 1) + "/" + str(len(wolfsberg)))
                    siren = get_SIREN(client, invoice[i].get_text())
                    print("SIREN : ", siren)
                    if siren != "N/A" and siren.isnumeric() :
                        answers_invoice["What is the SIRET of the counterparty ?"] = siren[:9]
                        break
                answers.update(convert_dict_for_app(answers_invoice,"Invoice", True))
                invoice.close()
            return answers
    except Exception as e:
        return jsonify({'error': 'Something went wrong during the document analysis : ' + str(e)}), 400
    

@app.route('/get_answers_scraping', methods=['POST'])
def get_answers_scraping() :
    client = init_client(os.getenv("OPENAI_API_KEY"))
    try:
        data = convert_app_dict_for_algo(request.get_json())
        print(data)
        answers_scraping = get_blank_questions_scraping_json("utils/questionsScraping.json")
        return_answer = {}
        print("Récupération du risque politique du pays via eulerhermes....")
        country = get_country_translation(client, data["Wolfsberg"]["In which country the counterparty is located ?"])
        print("Country translation : " + country )
        risk = get_political_risk(country)
        print("Score trouvé : " + risk)
        answers_scraping["EulerHermes"]["What is the political risk of the counterparty's country ?"] = str(risk)
        print("Récupération de la notation s&p du pays via tradingeconomics...")
        rating = get_sp_credit_rating(country)
        answers_scraping["TradingEconomic"]["What is the credit rating of the counterparty's country?"] = str(rating)
        print("Score trouvé : " + rating)
        print("Récupération des fuites de données via ihavebeenpwned...")
        pwned = is_pwned(data["Name"])
        print("Résultat trouvé : " + pwned)
        answers_scraping["IHaveBeenPwned"]["Has the counterparty been the victim of a data leak?"] = pwned
        if(data["Wolfsberg"]["Is the counterparty publicly traded or part of a publicly traded group?"] != "N/A" and data["Wolfsberg"]["Is the counterparty publicly traded or part of a publicly traded group?"] != "NO") :
            print("Analyse du cours de l'action via yahoofinance...")
            ticker = get_ticker(data["Name"])
            if ticker != None :
                hasdropped = has_dropped(ticker)
                answers_scraping["StockPrice"]["If the counterparty is publicly traded, has the value of its shares fallen significantly recently?"] = hasdropped
                answers_scraping["StockPrice"]["If the counterparty is publicly traded, is the value of its shares highly volatile?"] = hasdropped
        
        if "Invoice" in data.keys() :
             answers_scraping["SIREN"]["When was the counterparty founded ?"] = siren_info(data["Invoice"]["What is the SIRET of the counterparty ?"])
             return_answer.update(convert_dict_for_app(answers_scraping["SIREN"], "SIREN"))
        answers_scraping["AUTORITE_FIN"]["Is the counterparty blacklisted by a financial authority?"] = "NO"
        return_answer.update(convert_dict_for_app(answers_scraping["AUTORITE_FIN"], "AUTORITE_FIN"))
        return_answer.update(convert_dict_for_app(answers_scraping["StockPrice"], "StockPrice"))
        return_answer.update(convert_dict_for_app(answers_scraping["IHaveBeenPwned"], "IHaveBeenPwned"))
        return_answer.update(convert_dict_for_app(answers_scraping["EulerHermes"], "EulerHermes"))
        return_answer.update(convert_dict_for_app(answers_scraping["TradingEconomic"], "TradingEconomic"))
        return return_answer
    except Exception as e:
        return jsonify({'error': 'Something went wrong during the scraping : ' + str(e)}), 400

@app.route('/get_points', methods=['POST'])
def get_points() :
    try:
        return_dict = {}
        total_dict = {}
        pointsdict = get_points_dict("utils/points.json")
        client = init_client(os.getenv("OPENAI_API_KEY"))
        data = convert_app_dict_for_algo(request.get_json())
        print(data)
        wolfsberg_points = Wolfsberg_answers_2_points(client, data["Wolfsberg"], pointsdict["Wolfsberg"])
        total_dict["Wolfsberg"] = wolfsberg_points
        ici_points = answers_2_points( data["ICI"], pointsdict["ICI"])
        total_dict["ICI"] = ici_points
        euler_points = answers_2_points( data["EulerHermes"], pointsdict["EulerHermes"])
        total_dict["EulerHermes"] = euler_points
        sp_points = answers_2_points( data["TradingEconomic"], pointsdict["TradingEconomic"])
        total_dict["TradingEconomic"] = sp_points
        pwned_points = answers_2_points(data["IHaveBeenPwned"], pointsdict["IHaveBeenPwned"])
        total_dict["IHaveBeenPwned"] = pwned_points
        stock_points =  answers_2_points(data["StockPrice"], pointsdict["StockPrice"])
        total_dict["StockPrice"] = stock_points
        blacklist_points = answers_2_points(data["AUTORITE_FIN"], pointsdict["AUTORITE_FIN"])
        total_dict["AUTORITE_FIN"] = blacklist_points
        return_dict["points_details"] = total_dict
        return_dict["score"] = get_final_score(total_dict) 
        return_dict["notation"] = get_notation(return_dict["score"])
        return_dict["infos"] = {"Date": data["Wolfsberg"]["When was the counterparty founded ?"], "Country" : data["Wolfsberg"]["In which country the counterparty is located ?"]}
        return return_dict
    except Exception as e :
        return jsonify({'error': 'Something went wrong during the points conversion : ' + str(e)}), 400

if __name__ == '__main__':
    app.run(debug=True)



