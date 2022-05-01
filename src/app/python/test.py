import config
import slackweb

SLACK_PTOKEN = config.SLACK_PTOKEN

slack = slackweb.Slack(url=SLACK_PTOKEN)
slack.notify(text="Pythonからslackへ通知する")
print('Hello Python!')