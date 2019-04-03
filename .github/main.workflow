workflow "Deploy" {
  resolves = [
    "elgohr/Github-Release-Action@1.0",
    "tag",
  ]
  on = "push"
}

# Filter for tag
action "tag" {
  uses = "actions/bin/filter@master"
  args = "tag"
}

action "elgohr/Github-Release-Action@1.0" {
  uses = "elgohr/Github-Release-Action@1.0"
  needs = ["tag"]
  secrets = ["GITHUB_TOKEN"]
}
