from traitementJSON import *
from OpenAIAPI import *
pointsdict = get_points_dict("utils/points.json")

def Wolfsberg_answers_2_points(client, answerdict, pointdict) :
    finaldict = {}
    answer_keys = answerdict.keys()
    for question in pointdict.keys() :
        if question in answer_keys and answerdict[question] != "N/A" and  "not" not in answerdict[question].lower()  :
            finaldict[question] = pointdict[question][answerdict[question].upper()]
        elif question == "Was the counterparty founded less than 6 months ago?" and answerdict["When was the counterparty founded ?"] != "N/A":
            ans = is_6month__old(client, answerdict["When was the counterparty founded ?"])
            if ans == "YES" or ans == "NO" :
                finaldict[question] = pointdict[question][ans]
        elif question == "What is the country risk of the counterparty?" and answerdict["In which country the counterparty is located ?"] != "N/A":
            ans = get_country_region(client, answerdict["In which country the counterparty is located ?"])
            if ans in ["Other countries", "USA", "West Europe"] :
                finaldict[question] = pointdict[question][ans]
        else :
            finaldict[question] = None
    return finaldict

def answers_2_points(answerdict, pointdict) :
    finaldict = {}
    answer_keys = answerdict.keys()
    for question in pointdict.keys() :
        try:
            if question in answer_keys and answerdict[question] != "N/A" and  "not" not in answerdict[question].lower() :
                finaldict[question] = pointdict[question][answerdict[question].upper()]
            else :
                finaldict[question] = None
        except : 
            finaldict[question] = None
    return finaldict


def get_na_questions(pointdict, totaldict) :
    question_array = []
    total_keys = totaldict.keys()
    for key in pointdict.keys() :
        if key not in total_keys :
            for questions in pointdict[key].keys() :
                question_array.append(questions)
        else :
            for question in totaldict[key].keys() :
                if totaldict[key][question] == None :
                     question_array.append(pointdict[key][question])

    return question_array

def get_final_score(totaldict) :
    total_score = 0
    for key in totaldict.keys() :
        for questions in totaldict[key].keys() :
            if totaldict[key][questions] != None and totaldict[key][questions] != 0 :
                print(questions, totaldict[key][questions])
                total_score += totaldict[key][questions]
    return total_score


def get_notation(finalscore) :
    bareme = get_points_dict("utils/bareme.json")
    if finalscore <= bareme["LOW"] :
        return "LOW"
    elif finalscore <= bareme["MEDIUM"] :
        return "MEDIUM"
    else :
        return "HIGH"