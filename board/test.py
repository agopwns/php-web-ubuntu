from urllib.request import urlopen
from bs4 import BeatifulSoup

html = urlopen("https://naver.com/").read() # 내용 불러오기
soup = BeatifulSoup(html, "html.parser")
myUrls = soup.select('span.ah_k') # span.ah_k class 에 해당하는 값 가져오기\
cnt = 0;
for j in myUrls:
    cnt += 1
    print(str(cnt)+". "+j.text)
    if cnt == 20: # 20위까지 파싱
        break