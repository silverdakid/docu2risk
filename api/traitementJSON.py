import json

def get_questions_json_string(filename, question_type) : 
    f = open(filename, 'r');
    dict = json.load(f);
    for questions in dict[question_type] :
            dict[question_type][questions] = dict[question_type][questions]["answer_type"]
    return json.dumps(dict[question_type]); # question type : "Wolfsberg", "ICI", ...

def get_blank_questions_json(filename,  question_type) :
    f = open(filename, 'r')
    dict = json.load(f)[question_type]
    for question in dict.keys() :
        dict[question] = "N/A";
    return dict

def get_blank_questions_scraping_json(filename) :
    f = open(filename, 'r')
    dict = json.load(f)
    return dict

def get_blank_points_json(filename) :
    f = open(filename, 'r')
    dict = json.load(f)
    for type in dict.keys() :
        for question in type.keys() :
            dict[type][question] = None
    return dict

def get_points_dict(filename) :
    f = open(filename, 'r')
    return json.load(f)


def convert_dict_for_app(dict, docname, is_AI = False) :
    question_array = []
    info_dict = get_points_dict("utils/questionsAI.json")
    detail_dict = get_points_dict("utils/points.json")
    for key in dict :
        type = "text"
        values = []
        if docname in detail_dict.keys() :
            if key in detail_dict[docname].keys() :
                for val in detail_dict[docname][key].keys() :
                    values.append(val)
                if len(detail_dict[docname][key].keys()) > 2 :
                    type = "dropdown"
                else :
                    type = "radio"
        if(is_AI) :
            question_array.append({"name" : key, "answer" : dict[key], "scraping" : info_dict[docname][key]["scraping"], "type" : type, "answers" : values})
        else :
            question_array.append({"name" : key, "answer" : dict[key], "type" : type, "answers" : values})
    return_dict = {docname :  question_array}
    print(return_dict)
    return return_dict

def convert_app_dict_for_algo(dict) :
    return_dict = {}
    for key in dict :
        return_dict[key] = {}
        if key == "Name" :
            return_dict[key] = dict[key]
        else : 
            for element in dict[key]:
                print(element)
                return_dict[key][element["name"]] = element["answer"]
    return return_dict

def get_files_path() : 
   with open("utils/config.json", 'r') as file:
        config_data = json.load(file)
        return config_data.get('files_path')
   
def convert_wolfsberg_2_standard(dict_answer, wolfsberg_type) :
    f = open("utils/wolfsberg_AI.json", 'r');
    wolfsberg_dict = json.load(f)
    dict = {}
    for keys in dict_answer :
        dict[wolfsberg_dict[wolfsberg_type][keys]["real_question"]] = dict_answer[keys]
    return dict 

        