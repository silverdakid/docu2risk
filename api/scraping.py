from bs4 import BeautifulSoup
import requests
import lxml
import yfinance as yf ## install yfinance
import numpy as np ## pour éviter d'avoir des erreurs de types
import datetime
########################################

def get_political_risk(country) : 
    try:
        user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0"
        headers = {'User-Agent': user_agent}
        url = "https://www.allianz-trade.com/en_global/economic-research/country-reports/" + country.replace(" ", "-") + ".html"
        response = requests.get(url, headers=headers)
        if response.status_code == 200 :
            soup = BeautifulSoup(response.text, 'lxml')
            element = soup.select_one("#onemarketing-main-wrapper > div:nth-child(2) > div > div > div > div.histogramms-test > div > div > div > div > ul.country-reports__indicators.country-report-toggle.columns-five > li:nth-child(3) > img")
            elt = element["src"][len(element["src"]) - 5]
            if elt is None :
                return "N/A"
            else :
                return elt
        else :
            return "N/A"
    except Exception as e :
        print(e)
        return "N/A"
    
def get_sp_credit_rating(country) :
    try:
        user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0"
        headers = {'User-Agent': user_agent}
        url = "https://tradingeconomics.com/country-list/rating"
        response = requests.get(url, headers=headers)
        if response.status_code == 200 :
            soup = BeautifulSoup(response.text, 'lxml')
            element = soup.find('a', href=lambda href: href and country.lower().replace(" ", "-") in href and "rating" in href)
            score = element.find_parent('td').find_next_sibling('td').text.replace(" ","").replace("\n","").replace("\r", "")
            if score is None :
                return "N/A"
            else :
                return score
        else :
            return "N/A"
    except Exception as e :
        print(e)
        return "N/A"
    
    
def is_pwned(counterparty_name) :
    try:
        user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0"
        headers = {'User-Agent': user_agent}
        url = "https://haveibeenpwned.com/api/v2/breach/" + counterparty_name.replace(" ","")
        response = requests.get(url, headers=headers)
        if response.status_code == 200 :
            return "YES"
        else :
            return "NO"
    except Exception as e :
        print(e)
        return "N/A"
    
    

def get_ticker (company_name):
    try : 
        url = "https://query2.finance.yahoo.com/v1/finance/search"
        user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
        params = {"q": company_name, "quotes_count": 1}

        res = requests.get(url=url, params=params, headers={'User-Agent': user_agent})
        data = res.json()
        company_code = data['quotes'][0]['symbol']
        print(company_code)
        return company_code
    except Exception as e :
        print(e)
        return "N/A"
    
    
def has_dropped (company_code):
    company = yf.Ticker(company_code)
    try :
        company_history = company.history(period="1y", interval="1d")
        year_open = company_history['Open'].values[:1]
        year_open = float(np.asarray(year_open, dtype=np.float32, order='C')[0])
        today_close = company_history["Close"].values[-1:]
        today_close = float(np.asarray(today_close, dtype=np.float32, order='C')[0])
        print("Price open 1 year ago : ",year_open)
        print("Price close today : ",today_close)
        if(round((today_close - year_open) / year_open *100, 2)) < -30 : ## 
            return "YES"
        else :
            return "NO"
    except Exception as e:
        print(e)
        return "N/A"
    
def siren_info(siren):
    try :
        url = "https://www.pappers.fr/entreprise/"+siren
        user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
        res = requests.get(url=url, headers={'User-Agent': user_agent})
        if res.status_code == 200:
            soup = BeautifulSoup(res.content, 'html.parser')
            soup.prettify()
            div = soup.find("div", attrs={"class": "table-container grow"})
            tab = div.findAll("td")
            address = (tab[0].text).strip()
            date = (tab[-2].text).strip()
            date = datetime.date(int(date[6:]),int(date[3:5]),int(date[:2]))
            section = soup.find("section", attrs={"id": "activite"})
            activity = section.find("td").text
            return date.strftime("%B %d, %Y")
        else :
            return "N/A"
    except Exception as e :
        print(e)
        return "N/A"  
    
def blacklistUS(company):
    company = company.lower()
    url = "https://www.sec.gov/enforce/public-alerts"
    user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
    res = requests.get(url=url, headers={'User-Agent': user_agent})
    if res.status_code == 200:
        soup = BeautifulSoup(res.content, 'html.parser')
        soup.prettify()
        table = soup.find("table")
        companies = table.find_all("a")
        blacklist = []
        for i in companies:
            blacklist.append(((i.text).strip()).lower())
        if company in blacklist:
            return True
        return False
    else:
        return "N/A"
    
def blacklistFR(company):
    company = company.lower()
    url = "https://www.amf-france.org/fr/espace-epargna nts/proteger-son-epargne/listes-noires-et-mises-en-garde?page=0&key="+company
    user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
    res = requests.get(url=url, headers={'User-Agent': user_agent})
    if res.status_code == 200:
        soup = BeautifulSoup(res.content, 'html.parser')
        soup.prettify()
        try :
            button = soup.find("button",attrs={"class": "js-no-scrolling js-show-popin-button popin-black-list js-anchor-url"})
            companies = button.find_all("h2",class_="no-style-title")
            blacklist = []
            for i in companies:
                blacklist.append(((i.text).strip()).lower())
            print(len(blacklist))
            if company in blacklist:
                return True
            return False
        except:
            return False
    else:
        return("N/A")


def blacklistWorld(company):
    company = company.lower()
    url = "https://www.iosco.org/investor_protection/?subsection=investor_alerts_portal&Keywords="+company
    user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64)"
    res = requests.get(url=url, headers={'User-Agent': user_agent})
    if res.status_code == 200:
        soup = BeautifulSoup(res.content, 'html.parser')
        soup.prettify()
        try :
            table = soup.find("table", attrs={"class": "defaultStyle"})
            companies = table.find_all("a", attrs={"target": "_self"})
            blacklist = []
            for i in companies:
                blacklist.append(((i.text).strip()).lower())
            if company in blacklist:
                return True
            return False
        except:
            return False
    else:
        return "N/A"
    
def is_blacklisted(company_name) :
    blacklist = [blacklistFR(company_name), blacklistUS(company_name), blacklistWorld(company_name)]
    if True in blacklist : 
        return "YES"
    elif "N/A" in blacklist :
        return "N/A"
    else : 
        return "NO"