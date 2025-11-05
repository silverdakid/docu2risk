import requests ## install requests
import yfinance as yf ## install yfinance
import numpy as np ## pour éviter d'avoir des erreurs de types

def get_ticker (company_name):
    url = "https://query2.finance.yahoo.com/v1/finance/search"
    user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
    params = {"q": company_name, "quotes_count": 1}

    res = requests.get(url=url, params=params, headers={'User-Agent': user_agent})
    data = res.json()
    try :
        company_code = data['quotes'][0]['symbol']
        print(company_code)
        return company_code
    except:
        return None


def get_action (company_code):
    company = yf.Ticker(company_code)
    try :
        company_history = company.history(period="1y", interval="1d")
        year_open = company_history['Open'].values[:1]
        year_open = float(np.asarray(year_open, dtype=np.float32, order='C')[0])
        today_close = company_history["Close"].values[-1:]
        today_close = float(np.asarray(today_close, dtype=np.float32, order='C')[0])
        print("Price open 1 year ago : ",year_open)
        print("Price close today : ",today_close)
        if(round((today_close - year_open) / year_open *100, 2)) < -30 : 
            return "YES"
        else :
            return "NO"
    except:
        return "NO"




def get_currency (currency):
    ccy = ""
    if(currency == "USD"):
        ccy = "USDEUR=X"
    else:
        ccy = currency+"USD=X"
    dccy = yf.Ticker(ccy)
    try :
        dccy_info = dccy.info
        dccy_history = dccy.history(period="1y", interval="1d")
        dccy_open = dccy_history['Open'].values[:1]
        dccy_open = float(np.asarray(dccy_open, dtype=np.float32, order='C')[0])
        dccy_close = dccy_history["Close"].values[-1:]
        dccy_close = float(np.asarray(dccy_close, dtype=np.float32, order='C')[0])
        print("Price open 1 year ago : ",dccy_open)
        print("Close today : ",dccy_close)
        print("Annual % change : ",round((dccy_close - dccy_open) / dccy_open *100, 2),"%")
        print("200 days average : ",dccy.info['twoHundredDayAverage'])
        print("Name : ",dccy_info['longName'])
    except:
        print("Currency not found")



while True:
    type = input("Enter 1 for company or 2 for currency : ")
    if(type == "1"):
        company = input("Enter company name : ")
        company_code = get_ticker(company)
        if(company_code != None):
            get_action(company_code)
        else:
            print("Company not found")
    elif(type == "2"):
        currency = input("Enter currency name : ")
        get_currency(currency)
    else :
        print("Error")